<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class functions_m extends CI_Model {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function store_referer($custom_url=NULL)
    {
        // First clean this record so its current.
        $this->session->unset_userdata('refered_from');
        
        if($custom_url)
        {
            $this->session->set_userdata('refered_from', $custom_url);
        } else {
            // Update this record with fresh information.
            $this->session->set_userdata('refered_from', $_SERVER['HTTP_REFERER']);             
        }       
    }
    
    public function refered_from()
    {   
        return $this->session->userdata('refered_from');
    }
    
    public function success_message($message)
    {
        $this->session->set_flashdata('triggerMessage', TRUE);
        return $this->session->set_flashdata('message', $message);
    }
    
    public function error_message($message)
    {
        $this->session->set_flashdata('triggerError', TRUE);
        return $this->session->set_flashdata('error', $message);
    }
    
    public function convert_time($from_time, $to_time = 0, $include_seconds = true) 
    {
    	// If no 'To' time provided, use current time.
    	if($to_time == 0) { $to_time = time(); }
    
    	$distance_in_minutes = round(abs($to_time - $from_time) / 60);
    	$distance_in_seconds = round(abs($to_time - $from_time));
        
    	if ($distance_in_minutes >= 0 and $distance_in_minutes <= 1) {
    
    		if (!$include_seconds) {
    
    			return ($distance_in_minutes == 0) ? 'less than a minute' : '1 minute';
    
    		} else {
    
    			if ($distance_in_seconds >= 0 and $distance_in_seconds <= 4) {
    
    				return 'less than 5 seconds';
    
    			} elseif ($distance_in_seconds >= 5 and $distance_in_seconds <= 9) {
    
    				return 'less than 10 seconds';
    
    			} elseif ($distance_in_seconds >= 10 and $distance_in_seconds <= 19) {
    
    				return 'less than 20 seconds';
    
    			} elseif ($distance_in_seconds >= 20 and $distance_in_seconds <= 39) {
    
    				return 'half a minute';
    
    			} elseif ($distance_in_seconds >= 40 and $distance_in_seconds <= 59) {
    
    				return 'less than a minute';
    
    			} else {
    
    				return '1 minute';
    
    			}
    
    		}
    
    	} elseif ($distance_in_minutes >= 2 and $distance_in_minutes <= 44) {
    
    		return $distance_in_minutes . ' minutes';
    
    	} elseif ($distance_in_minutes >= 45 and $distance_in_minutes <= 89) {
    
    		return '1 hour';
    
    	} elseif ($distance_in_minutes >= 90 and $distance_in_minutes <= 1439) {
    
    		return ' ' . round(floatval($distance_in_minutes) / 60.0) . ' hours';
    
    	} elseif ($distance_in_minutes >= 1440 and $distance_in_minutes <= 2879) {
    
    		return '1 day';
    
    	} elseif ($distance_in_minutes >= 2880 and $distance_in_minutes <= 43199) {
    
    		return ' ' . round(floatval($distance_in_minutes) / 1440) . ' days';
    
    	} elseif ($distance_in_minutes >= 43200 and $distance_in_minutes <= 86399) {
    
    		return ' 1 month';
    
    	} elseif ($distance_in_minutes >= 86400 and $distance_in_minutes <= 525599) {
    
    		return round(floatval($distance_in_minutes) / 43200) . ' months';
    
    	} elseif ($distance_in_minutes >= 525600 and $distance_in_minutes <= 1051199) {
    
    		return ' 1 year';
    
    	} else {
    
    		return 'over ' . round(floatval($distance_in_minutes) / 525600) . ' years';
    
    	}
    }
    
    /**
    * Send Email Function
    * 
    * @description - Sends a email to the admin.
    * @since 2.0.0
    * @package Dove Forums
    **/
    public function send_admin_email($message, $subject)
    {
        $this->email->initialize($this->load->config('email'));
        $this->email->clear();
        $this->email->from($this->settings->get_setting('site_email'), $this->settings->get_setting('site_name'));
        $this->email->to($this->settings->get_setting('site_email'));
        $this->email->subject($subject);
        $this->email->message($message);
        if(!$this->email->send())
        {
            $this->function->error_message('There was a problem with the email.');
        }        
    }
    
    /**
    * Send User Email Function
    * 
    * @description - Sends a email to a user.
    * @since 2.0.0
    * @package Dove Forums
    **/
    public function send_user_email($email, $message, $subject)
    {
        $this->email->initialize($this->load->config('email'));
        $this->email->clear();
        $this->email->from($this->settings->get_setting('site_email'), $this->settings->get_setting('site_name'));
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message);
        if(!$this->email->send())
        {
            $this->function->error_message('There was a problem with the email.');
            return false;
        } else {
            return true;
        }       
    }
    
    /**
    * Send Mass Email Function
    * 
    * @description - Sends a email to multiple users.
    * @since 2.0.0
    * @package Dove Forums
    **/
    public function send_mass_email($emails, $message, $subject)
    {
        $this->email->initialize($this->load->config('email'));
        $this->email->clear();
        $this->email->from($this->settings->get_setting('site_email'), $this->settings->get_setting('site_name'));
        $this->email->bcc($emails);
        $this->email->subject($subject);
        $this->email->message($message);
        if(!$this->email->send())
        {
            $this->function->error_message('There was a problem with the email.');
            return false;
        } else {
            return true;
        }       
    }
    
   function unique_permalink($permalink, $table)
    {
    	// Create a permalink from the name, hyphen separated
    	$permalink = url_title($permalink, 'dash');
    	
    	// Query the database for existence of this page slug
        $exists = $this->check_permalink($permalink, $table);
    	
    	// Aww shit, we found it
    	if ( $exists)
    	{
    		$suffix = 1;
    		
    		do {
    			$alt_permalink = substr( $permalink, 0, 255 - ( strlen( $suffix ) + 1 ) ) . "-$suffix";
    			$permalink_check = $this->check_permalink($alt_permalink, $table);
    			$suffix++;
    		} while ( $permalink_check );
    		
    		$permalink = $alt_permalink;
    		
    		return $permalink;
    	}
    	// Okay, that permalink slug wasn't found, we're good
    	else
    	{
    		return $permalink;
    	}
    }
    
    function check_permalink($permalink, $table)
    {
    	// Query the database for existence of this page slug
        $query = $this->db->where('permalink', $permalink)->get($table);
    	
    	// Return true if results found, otherwise return false
    	return ($query->num_rows() >= 1) ? TRUE : FALSE;
    }
}