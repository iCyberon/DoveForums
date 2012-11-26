<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends MX_Controller{

    public function __construct()
    {
        parent::__construct();
        
        // Load language files.
        $this->load->language('forums/success_messages');
        $this->load->language('forums/error_messages');
        $this->load->language('forums/general');
        $this->load->language('forums/posts');
        
        // Load in required model files.
        $this->load->model('forums/forums_m', 'forums');
        $this->load->model('forums/threads_m', 'threads');
        $this->load->model('forums/posts_m', 'posts');
        $this->load->model('functions_m', 'functions');
    }
    
    public function construct_template($data, $page_tpl, $page_title, $layout=NULL)
    {
        if(!$layout)
        {
            $layout = 'default_layout';
        } else {
            $layout = $layout;
        }

        // Load language files.
        $languages = array(
            'posts' => $this->load->language('forums/posts'),
        );
        
        // Send to the parser.
        $this->parser->append('lang', $languages);
        
        // Build Meta Data.
        $meta = array(
            'keywords' => $this->settings->get_setting('site_keywords'),
            'description' => $this->settings->get_setting('site_description'),
            'author' => $this->settings->get_setting('site_author'),
            'site_title' => ''.$this->settings->get_setting('site_name').' - '.$page_title.'',
        );
        
        // Send to the parser.
        $this->parser->append('meta', $meta);
        
        // Define the template.
        $this->parser->theme('default');

        // Define main sections.
        $config['show'] = false;
        
        // Header data.
        $header_data = array(
            'site_url' => site_url(),
            'site_title' => $this->settings->get_setting('site_name'),
            'login_link' => anchor(''.site_url().'/account/login', $this->lang->line('link_sign_in')),
            'register_link' => anchor(''.site_url().'/account/register', $this->lang->line('link_register')),
            'logout_link' => anchor(''.site_url().'/account/logout/', $this->lang->line('link_logout')),
            'manage_account_link' => anchor(''.site_url().'/account/manage/', $this->lang->line('link_manage_account')),
            'active_user' => $this->session->userdata('username'),
            'avatar' => img($this->gravatar->get_gravatar($this->session->userdata('email'), $this->settings->get_setting('gravatar_rating'), '35')),
            'logged_in' => $this->session->userdata('logged_in'),
        );
        
        // Navigation data.
        $navigation_data = array(
            'site_url' => site_url(),
            'logged_in' => $this->session->userdata('logged_in'),
            'is_admin' => $this->dove_auth->is_admin(),
        );
        
        // Messages data.
        $messages_data = array(
            'message' => $this->session->flashdata('triggerMessage'),		
		    'error' => $this->session->flashdata('triggerError'),	
            'successMessage' => $this->session->flashdata('message'),
            'errorMessage' => $this->session->flashdata('error'),  
        );
        
        // Footer data.
        $footer_date = array(
            'test' => 'test',
        );
        
        // Main Template Data
        $main_template_data = array(
            'header' => $this->parser->parse('header', $header_data, $config),
            'navigation' => $this->parser->parse('navigation', $navigation_data, $config),
            'messages' => $this->parser->parse('messages', $messages_data, $config),
            'page_content' => $this->parser->parse($page_tpl, $data, $config),
            'footer' => $this->parser->parse('footer', $footer_date, $config),
        );

        // Construct the template.
        $this->parser->parse($layout, $main_template_data);
    }
    
}