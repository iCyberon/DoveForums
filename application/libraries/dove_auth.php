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

class dove_auth {
    /** 
    * Codeigniter Global
    * 
    * @var string
    **/
    protected $ci;
     
    /**
    * Account Status
    * 
    * @var string
    **/
    protected $status;
      
    /**
    * __construct
    * 
    * @return void
    **/ 
    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('email');
        $this->ci->load->model('dove_auth_model');
        $this->ci->load->model('functions_m', 'function');
        $this->ci->load->language('account/account', $this->ci->session->userdata('user_language'));
    }
       
    /**
    * Activate User Account
    * 
    * @return void
    **/
    public function activate($code, $username)
    {
        return $this->ci->dove_auth_model->activate($code, $username);
    }
        
    /**
    * Deactivate User Account
    * 
    * @return void
    **/
    public function deactivate($identity)
    {
        return $this->ci->dove_auth_model->deactivate($identity);
    }
    
    /**
    * Add New User Group
    * 
    * @return void
    * @author Chris Baines
    **/
    public function add_group($group_name)
    {
        return $this->ci->dove_auth_model->add_group($group_name);
    }
    
    /**
    * Remove User Group
    * 
    * @return void
    * @author Chris Baines
    **/
    public function remove_group($group_name)
    {
        return $this->ci->dove_auth_model->remove_group($group_name);
    }
         
    /**
    * Change User Password
    * 
    * @return void
    **/
    public function change_password($identity, $old, $new)
    {
        return $this->ci->dove_auth_model->change_password($identity, $old, $new);
    }
          
    /**
    * Forgot Password
    * 
    * @return void
    **/
    public function forgotten_password($email)
    {
        $forgotten_password = $this->ci->dove_auth_model->forgotten_password($email);
            
        if ($forgotten_password)
        {
            // Get user information
            $profile = $this->ci->dove_auth_model->profile($email);
            
            $data = array(
                'identity' => $profile->{$this->ci->config->item('identity')},
                'forgotten_password_code' => $this->ci->dove_auth_model->forgotten_password_code,
            );
            
            return $data;
        } else {
            return false;
        }
    }
    
    /**
    * Forgotten Password Complete
    * 
    * @return void
    **/
     
    public function forgotten_password_complete($code)
    {
        $identity = $this->ci->config->item('identity');
        $profile = $this->ci->dove_auth_model->profile($code);
        $forgotten_password_complete = $this->ci->dove_auth_model->forgotten_password_complete($code);
        
        if($forgotten_password_complete)
        {
            $data = array(
                'identity' => $profile->{$identity},
                'new_password' => $this->ci->dove_auth_model->new_password,
            );

            return $data;
        }else{
            return false;
        }
    }
    
    /**
    * Resigter 
    * 
    * @return void
    **/
    public function register($username, $password, $email)
    {       
        $email_activation = $this->ci->config->item('email_activation');
        $email_folder = $this->ci->config->item('email_templates');
        
        $activation_code = $this->ci->dove_auth_model->generate_activation_code();
        
        if(!$email_activation)
        {
            return $this->ci->dove_auth_model->register($username, $password, $email, $activation_code);
        } else {
            
            $register = $this->ci->dove_auth_model->register($username, $password, $email, $activation_code);
            
            $site_name = $this->ci->settings->get_setting('site_name');
            
            if($register == true)
            {
                // Prep email data.
                $data = array(
                    'username' => $username,
                    'activation_link' => anchor(''.site_url().'/account/activate/'.$activation_code.'/'.$username.'/', $this->ci->lang->line('email_activation_link')),
                );
                    
                return $data;
            } else {
                return false;
            }
        }
    }
    
    /**
    * Login
    * 
    * @return void
    **/
    public function login($identity, $password)
    {
        return $this->ci->dove_auth_model->login($identity, $password);
    }
    
    /**
    * Logout
    * 
    * @return void
    **/
    public function logout()
    {
        $identity = $this->ci->config->item('identity');
        $this->ci->session->unset_userdata($identity);
        $this->ci->session->unset_userdata('username');
        $this->ci->session->unset_userdata('email');
        $this->ci->session->unset_userdata('group_id');
        $this->ci->session->unset_userdata('user_id');
        $this->ci->session->unset_userdata('location');
        $this->ci->session->unset_userdata('first_name');
        $this->ci->session->unset_userdata('last_name');
        $this->ci->session->unset_userdata('logged_in');
        $this->ci->session->unset_userdata('user_language');
        $this->ci->session->sess_destroy();
        
        $this->ci->session->set_userdata('user_id', '8000000');
        
        return true;
    }
    
    /**
    * Logged In
    *
    * @return void
    **/
    public function logged_in()
    {
        $identity = $this->ci->config->item('identity');
        return ($this->ci->session->userdata($identity)) ? true : false;
    }
    
    /**
    * Profile
    * 
    * @return void
    **/
    public function profile($username)
    {
        return $this->ci->dove_auth_model->profile($username);
    }
    
    /**
    * Is Admin
    * 
    * @return void
    **/
    public function is_admin()
    {
        $admin_group = $this->ci->config->item('admin_group');
        $user_group = $this->group_by_id($this->ci->session->userdata('group_id'));
        
        return $user_group == $admin_group;
    }
    
    public function is_moderator()
    {
        $moderator_group = $this->ci->config->item('moderator_group');
        $user_group = $this->group_by_id($this->ci->session->userdata('group_id'));
        
        return $user_group == $moderator_group;
    }
    
    public function group_by_id($group_id)
    {
        return $this->ci->dove_auth_model->group_by_id($group_id);
    }
}