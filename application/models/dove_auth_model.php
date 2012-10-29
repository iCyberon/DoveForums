<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * 
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst. It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package Dove Forums
 * @copyright Copyright (c) 2012 - Christopher Baines
 * @license http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link http://doveforums.com
 * @since Version 1.0.4
 * @author Christopher Baines
 * 
 */

class dove_auth_model extends CI_Model 
{
    /** 
    * Holds an array of tables used in Dove Auth
    * 
    * @var string
    **/
    public $tables = array();
    
    /**
    * Activation Code
    * 
    * @var string
    **/
    public $activation_code;
    
    /**
    * Forgotten Password Key
    *
    * @var string
    **/
    public $forgotten_password_code;
    
    /**
    * New Password
    * 
    * @var string
    **/
    public $new_password;
    
    /**
    * Identity
    * 
    * @var string
    **/
    public $identity;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->config('dove_auth');
        $this->load->helper('cookie');
        
        $this->tables = $this->config->item('tables');
        $this->columns = $this->config->item('columns');
        
        $this->identity_column = $this->config->item('identity');
        
    }   
    
    public function hash_password($password = false)
    {
        $salt_length = $this->config->item('salt_length');
        
        if($password === false)
        {
            return false;
        }
        
        $salt = $this->salt();
        
        $password = $salt . substr(sha1($salt . $password), 0, -$salt_length);
        
        return $password;
    }
    
    public function hash_password_db($identity = false, $password = false)
    {
        $identity_column = $this->config->item('identity');
        $users_table = $this->tables['users'];
        $salt_length = $this->config->item('salt_length');
        
        if ($identity === false || $password === false)
        {
            return false;
        }
        
        $query = $this->db->select('password')
                            ->where($identity_column, $identity)
                            ->limit('1')
                            ->get($users_table);
        
        $result = $query->row();
        
        if($query->num_rows() != 1)
        {
            return false;
        }
        
        $salt = substr($result->password, 0, $salt_length);
        $password = $salt . substr(sha1($salt . $password), 0, -$salt_length);
        
        return $password;
    }
    
    public function salt()
    {
        return substr(md5(uniqid(rand(), true)), 0, $this->config->item('salt_length'));
    }
    
    public function activate($code = false, $username)
    {
        $identity_column = $this->config->item('identity');
        $users_table = $this->tables['users'];
        $activation_code = $code;
        $username = $username;
        
        $data = array(
            'user_active' => 'yes',
            'activation_code' => '',
        );
        
        $this->db->where(array('username' => $username, 'activation_code' => $activation_code));
        $this->db->update($users_table, $data);
        
        return ($this->db->affected_rows() == 1) ? true : false;
    }
    
    public function generate_activation_code()
    {
        $activation_code = sha1(md5(microtime()));
        
        return $activation_code;
    }
    
    public function change_password($identity = false, $old = false, $new = false)
    {
        $identity_column = $this->config->item('identity');
        $users_table = $this->tables['users'];
        
        if($identity === false || $old === false || $new === false)
        {
            return false;
        }
        
        $query = $this->db->select('password')
                            ->where($identity_column, $identity)
                            ->limit('1')
                            ->get($users_table);
        
        $result = $query->row();
        
        $db_password = $result->password;
        $old = $this->hash_password_db($identity, $old);
        $new = $this->hash_password($new);
        
        if($db_password === $old)
        {
            $data = array(
                'password' => $new,
            );
            
            $this->db->update($users_table, $data, array($identity_column => $identity));
            
            return ($this->db->affected_rows() == 1) ? true : false;
        }
        
        return false;
    }
    
    public function username_check($username = false)
    {
        $users_table = $this->tables['users'];
        
        if($username === false)
        {
            return false;
        }
        
        $query = $this->db->select('id')
                            ->where('username', $username)
                            ->limit('1')
                            ->get($users_table);
        
        if($query->num_rows() == 1)
        {
            return true;
        }
        
        return false;
    }
    
    public function email_check($email = false)
    {
        $users_table = $this->tables['users'];
        
        if($email === false)
        {
            return false;
        }
        
        $query = $this->db->select('id')
                            ->where('email', $email)
                            ->limit('1')
                            ->get($users_table);
        if($query->num_rows() == 1)
        {
            return true;
        }
        
        return false;
    }
    
    protected function identity_check($identity = false)
    {
        $identity_column = $this->config->item('identity');
        $users_table = $this->tables['users'];
        
        if($identity === false)
        {
            return false;
        }
        
        $query = $this->db->select('id')
                            ->where($identity_column, $identity)
                            ->limit(1)
                            ->get($users_table);
        
        if($query->num_rows() == 1)
        {
            return true;
        }
        
        return false;
    }
    
    public function forgotten_password($email = false){
        $users_table = $this->tables['users'];
        
        if($email === false)
        {
            return false;
        }
        
        $query = $this->db->select('forgotten_password_code')
                            ->where('email', $email)
                            ->limit(1)
                            ->get($users_table);
        
        $result = $query->row();
        
        $code = $result->forgotten_password_code;
        
        if(empty($code))
        {
            $key = $this->hash_password(microtime().$email);
            
            $this->forgotten_password_code = $key;
            
            $data = array(
                'forgotten_password_code' => $key
            );
            
            $this->db->update($users_table, $data, array('email' => $email));
            
            return ($this->db->affected_rows() == 1) ? true : false;
        } else {
            return false;
        }
    }
    
    public function forgotten_password_complete($code = false)
    {
        $users_table = $this->tables['users'];
        $identity_column = $this->config->item('identity');
        
        if($code === false)
        {
            return false;
        }
        
        $query = $this->db->select('id')
                            ->where('forgotten_password_code', $code)
                            ->limit(1)
                            ->get($users_table);
        
        $result = $query->row();
        
        if($query->num_rows() > 0)
        {
            $salt = $this->salt();
            $password = $this->hash_password($salt);
            
            $this->new_password = $salt;
            
            $data = array(
                'password' => $password,
                'forgotten_password_code' => '0',
            );
            
            $this->db->update($users_table, $data, array('forgotten_password_code' => $code));
            
            return true;
        }
        
        return false;
    }
    
    /**
    * Add New Group
    * @author Chris Baines
    **/
    public function add_group($group_name = false)
    {
        $groups_table = $this->tables['groups'];
        
        if($group_name === false)
        {
            return false;
        }
        
        $query = $this->db->select('name')
                            ->where('name', $group_name)
                            ->limit(1)
                            ->get($groups_table);
                            
        $result = $query->row();
        
        if($query->num_rows == 0)
        {
            $data = array(
                'name' => $name,
                'description' => $description,
            );
            
            $this->db->insert($groups_table, $data);
            
            return ($this->db->affected_rows() > 0) ? true : false;
        }
        
        return false;
    }
    
    /**
    * Remove Group Function
    * @author Chris Baines
    **/
    public function remove_group($group_name = false)
    {
        $groups_table = $this->tables['groups'];
        
        if($group_name === false)
        {
            return false;
        }
        
        $query = $this->db->select('name')
                            ->where('name', $group_name)
                            ->limit(1)
                            ->get($groups_table);
                            
        $result = $query->row();
        
        if($query->num_rows() > 0)
        {
            $this->db->delete($groups_table, array('name' => $group_name));
            
            return true;
        }
        
        return false;
    }
    
    public function get_username_from_email($email)
    {
        $users_table = $this->tables['users'];
        
        $this->db->select('username');
        
        $options = array(
            'email' => $email,
        );
        
        $query = $this->db->get_where($users_table, $options);
        
        if($query->num_rows() > 0)
        {
            return $query->row('username');
        } else {
            return false;
        }
    }
    
    public function profile($identity = false)
    {
        $users_table = $this->tables['users'];
        $groups_table = $this->tables['groups'];
        $meta_table = $this->tables['meta'];
        $meta_join = $this->config->item('join');
        $identity_column = 'username';
        
        if($identity === false)
        {
            return false;
        }
        
        $username = $this->get_username_from_email($identity);
        
        $this->db->select($users_table.'.id, '.
                            $users_table.'.username, ' . 
                            $users_table.'.email, ' .
                            $users_table.'.ip_address, ' .
                            $users_table.'.signature, ' .
                            $users_table.'.account_created, ' .
                            $users_table.'.last_login, ' .
                            $meta_table.'.first_name, ' .
                            $meta_table.'.last_name, ' .
                            $meta_table.'.location, ' .
                            $groups_table.'.name AS `group`');
        if(!empty($this->columns))
        {
            foreach($this->columns as $value)
            {
                $this->db->select($meta_table.'.'.$value);
            }
        }
        
        $this->db->from($users_table);
        $this->db->join($meta_table, $users_table.'.id = '.$meta_table.'.'.$meta_join, 'left');
        $this->db->join($groups_table, $users_table.'.group_id = '.$groups_table.'.id', 'left');
        
        if(strlen($identity) === 40)
        {
            $this->db->where($users_table.'.forgotten_password_code', $identity);
        } else {
            $this->db->where($users_table.'.'.$identity_column, $username);
        }
        
        $this->db->limit(1);
        $i = $this->db->get();
        
        return ($i->num_rows > 0) ? $i->row() : false;
    }
    
    public function register($username = false, $password = false, $email = false, $activation_code)
    {
        $users_table = $this->tables['users'];
        $meta_table = $this->tables['meta'];
        $groups_table = $this->tables['groups'];
        $meta_join = $this->config->item('join');
        $additional_columns = $this->config->item('columns');
        
        $group_id = $this->input->post('group_id');
        
        if($username === false || $password === false || $email === false)
        {
            return false;
        }
        
        // Group ID
        if(!$group_id)
        {
            $query = $this->db->select('id')->where('name', $this->config->item('default_group'))->get($groups_table);
            $result = $query->row();
            $group_id = $result->id;
        } else {
            $group_id = $this->input->post('group_id');
        }
        
        //IP Address
        $ip_address = $this->input->ip_address();
        
        if($this->input->post('active') == 'yes')
        {
            $active = 'yes';
        } else {
            $active = 'no';
        }
        
        // Users Table
        $data = array(
            'username' => $username,
            'password' => $this->hash_password($password),
            'email' => $email,
            'group_id' => $group_id,
            'ip_address' => $ip_address,
            'account_created' => now(),
            'user_active' => $active,
            'activation_code' => $activation_code,
            'last_login' => now(),
        );
        
        $this->db->insert($users_table, $data);
        
        if(!$this->db->affected_rows())
        {
            return false;
        }
        
        // Meta Table
        $id = $this->db->insert_id();
        
        $data = array($meta_join => $id);
        
        if(!empty($additional_columns))
        {
            foreach($additional_columns as $input)
            {
                $data[$input] = $this->input->post($input);
            }
        }
        
        $this->db->insert($meta_table, $data);
        
        if(!$this->db->affected_rows())
        {
            return false;
        }
        
        // User Roles table
        $data = array( 
            'userID' => $id,
            'roleID' => $group_id,
        );
        
        $this->db->insert('user_roles', $data);
        
        if(!$this->db->affected_rows())
        {
            return false;
        } else {
            return true;
        }
    }
    
    public function login($identity = false, $password = false)
    {
        $identity_column = $this->config->item('identity');
        $users_table = $this->tables['users'];
        $groups_table = $this->tables['groups'];
        $meta_table = $this->tables['meta'];
        $meta_join = $this->config->item('join');
        
        if($identity === false || $password === false || $this->identity_check($identity) == false)
        {
            return false;
        }
        
        $this->db->select('
            '.$users_table.'.'.$identity_column.', 
            '.$users_table.'.activation_code, 
            '.$users_table.'.username, 
            '.$users_table.'.email, 
            '.$users_table.'.password,
            '.$users_table.'.group_id, 
            '.$users_table.'.last_login,
            '.$users_table.'.id, '
        );
        if(!empty($this->columns))
        {
            foreach($this->columns as $value)
            {
                $this->db->select($meta_table.'.'.$value);
            }
        }
        $this->db->from($users_table);
        $this->db->where($identity_column, $identity);
        $this->db->limit(1);
        $this->db->join($meta_table, 'users.id = meta.user_id');
        
        $query = $this->db->get();
        
        $result = $query->row();
        
        if($query->num_rows() > 0)
        {
            $password = $this->hash_password_db($identity, $password);
            
            if(!empty($result->activation_code)) { return false; }
            
            if($result->password === $password)
            {
                $this->session->set_userdata($identity_column, $result->{$identity_column});
                $this->session->set_userdata('username', $result->username);
                $this->session->set_userdata('email', $result->email);
                $this->session->set_userdata('group_id', $result->group_id);
                $this->session->set_userdata('user_id', $result->id);
                $this->session->set_userdata('location', $result->location);
                $this->session->set_userdata('first_name', $result->first_name);
                $this->session->set_userdata('last_name', $result->last_name);
                $this->session->set_userdata('logged_in', true);
                $this->session->set_userdata('last_login', $result->last_login);
                $this->session->set_userdata('user_language', $result->user_language);
                
                $this->update_last_login($result->id);
                
                $group = $this->db->select('name')
                                    ->where('id', $result->id)
                                    ->get($groups_table)
                                    ->row();
                
                $this->session->set_userdata('group', $group->name);
                
                return true;
            }
        }
        return false;
    }
    
    public function update_last_login($user_id)
    {
        $this->load->helper('date');
        
        $users_table = $this->tables['users'];
        
        $last_login = $this->db->select('last_login')
                    ->where('id', $user_id)
                    ->get($users_table)
                    ->row();
        
        $data = array(
            'previous_last_login' => $last_login->last_login,
        );
        
        $this->db->where('id', $user_id);
        $this->db->update($users_table, $data);
        
        $data = array(
            'last_login' => now(),
        );
        
        $this->db->where('id', $user_id);
        $this->db->update($users_table, $data);
        
        if($this->db->affected_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
    
    public function group_by_id($group_id)
    {
        $groups_table = $this->tables['groups'];
        
        $query = $this->db->select('name')
                    ->where('id', $group_id)
                    ->limit(1)
                    ->get($groups_table);
                    
        if($query->num_rows() > 0)
        {
            return $query->row('name');
        }
        
        return false;
    }
    
    public function get_activation_code($username)
    {
        // Set the select.
        $this->db->select('activation_code');
        
        // Set the limit.
        $this->db->limit('1');
        
        // Set some options.
        $options = array(
            'username' => $username,
        );
        
        // Perform the query.
        $query = $this->db->get_where('users', $options);
        
        // Return the results.
        if($query->num_rows() > 0)
        {
            return $query->row('activation_code');
        } else {
            return false;
        }
    }
}