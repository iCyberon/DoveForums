<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class account_m extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
        
        // Small fix for validation callbacks using HMVC.
        $this->form_validation->CI =& $this;
    }
    
    /**
     * username_check() Function.
     * 
     * @Description Checks to see if the username exists in the database, if not it also checks to banned usernames.
     *
     * @author Chris Baines
     *
     * @param string $username
     * @return true/false.
     */  
    public function username_check($username)
    {
        // Select the username from the database.
        $this->db->select('username');
        
        // Setup some options.
        $options = array(
            'username' => $username,
        );
        
        $query = $this->db->get_where('users', $options);
        
        if(!$query->num_rows())
        {
            // The username is not in the database.
            
            // Now let`s check the list of banned usernames.
            $this->db->select('username');
            
            $query = $this->db->get('banned_usernames');
            
            $result = $query->result_array();
            
            foreach($result as $name)
            {
                
                if($username == $name['username'])
                {
                    $this->form_validation->set_message('username_check', $this->lang->line('callback_banned_username'));
                    return false;
                }
            }
            
            return true;
        } else {
            // The username is already taken.
            $this->form_validation->set_message('username_check', $this->lang->line('callback_username_check'));
            return false;
        }
    }

    /**
     * email_check() Function.
     * 
     * @Description Checks to see if the email is in the database.
     *
     * @author Chris Baines
     *
     * @param string $email
     * @return true/false.
     */      
    public function email_check($email)
    {
        // Select the email from the database.
        $this->db->select('email');
        
        // Setup some options.
        $options = array(
            'email' => $email,
        );
        
        // Run the query
        $query = $this->db->get_where('users', $options);
        
        if(!$query->num_rows())
        {
            // The email is not in the database.
            return true;
        } else {
            // The email is in the database.
            $this->form_validation->set_message('email_check', $this->lang->line('callback_email_check'));
            return false;
        }        
    }
}