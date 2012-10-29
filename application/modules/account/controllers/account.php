<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class account extends MY_Controller {
    
    // Validation data.
    private $validation_rules = array(
        'login' => array(
            //0
            array(
                'field' => 'username',
                'label' => 'lang:rules_label_username',
                'rules' => 'required|xss_clean',
            ),
            //1
            array(
                'field' => 'password',
                'label' => 'lang:rules_label_password',
                'rules' => 'required|min_length[6]|max_length[20]|xss_clean',
            ),
        ),
        
        'register' => array(
            //0
            array(
                'field' => 'username',
                'label' => 'lang:rules_label_username',
                'rules' => 'required|callback_username_check|xss_clean',
            ),
            //1
            array(
                'field' => 'password',
                'label' => 'lang:rules_label_password',
                'rules' => 'required|min_length[6]|max_length[20]|matches[confirm_password]|xss_clean',
            ),
            //2
            array(
                'field' => 'confirm_password',
                'label' => 'lang:rules_label_confirm_password',
                'rules' => 'required|min_length[6]|max_length[20]|xss_clean',
            ),
            //3
            array(
                'field' => 'email_address',
                'label' => 'lang:rules_label_email_address',
                'rules' => 'required|valid_email|matches[confirm_email_address]|callback_email_check|xss_clean',
            ),
            //4
            array(
                'field' => 'confirm_email_address',
                'label' => 'lang:rules_label_confirm_email_address',
                'rules' => 'required|valid_email|xss_clean',
            ),
        ),
    );
    
    // Form fields.
    private $form_fields = array(
        'login' => array(
            //0
            array(
                'id' => 'username',
                'name' => 'username',
                'class' => 'text',
            ),
            //1
            array(
                'id' => 'password',
                'name' => 'password',
                'type' => 'password',
                'class' => 'text',
            ),
        ),
        
        'register' => array(
            //0
            array(
                'id' => 'username',
                'name' => 'username',
                'class' => 'text',
            ),
            //1
            array(
                'id' => 'password',
                'name' => 'password',
                'type' => 'password',
                'class' => 'text',
            ),
            //2
            array(
                'id' => 'confirm_password',
                'name' => 'confirm_password',
                'type' => 'password',
                'class' => 'text',
            ),
            //3
            array(
                'id' => 'email_address',
                'name' => 'email_address',
                'class' => 'text',
            ),
            //4
            array(
                'id' => 'confirm_email_address',
                'name' => 'confirm_email_address',
                'class' => 'text',
            ),
            //5
            array(
                'id' => 'group_id',
                'name' => 'group_id',
            ),
        ),
    );
    
    //Constructor Method
    public function __construct()
    {
        // Call Constructor
        parent::__construct();
        
        // Load language files.
        $this->load->language('account/account', $this->session->userdata('user_language'));
    }
    
    public function login()
    {
        // Store the referer.
        $this->function->store_referer();
        
        if($this->session->userdata('logged_in'))
        {
            // The user is logged in, redirect with a message.
            $this->function->error_message($this->lang->line('error_logged_in'));
                
            // Redirect.
            redirect($this->function->refered_from());
        } else {
                
            // Validation rules
            $this->form_validation->set_rules($this->validation_rules['login']);
                
            if($this->form_validation->run() == FALSE)
            {       
                // Data array.
                $data = array(
                    'form_open' => form_open(''.site_url().'/account/login/'),
                    'form_close' => form_close(), 
                    'login_fieldset' => form_fieldset('Login'),
                    'close_fieldset' => form_fieldset_close(),
                    // Username
                    'username_label' => form_label($this->lang->line('label_username')),
                    'username_field' => form_input($this->form_fields['login']['0'], set_value($this->form_fields['login']['0']['name'])),
                    'username_field_error' => form_error($this->form_fields['login']['0']['name'], '<div class="error">', '</div>'),
                    // Password
                    'password_label' => form_label($this->lang->line('label_password')),
                    'password_field' => form_input($this->form_fields['login']['1'], set_value($this->form_fields['login']['1']['name'])),
                    'password_field_error' => form_error($this->form_fields['login']['1']['name'], '<div class="error">', '</div>'),
                    // Buttons
                    'submit_button' => form_submit(array( 'name' => 'submit', 'class' => 'button blue light '), $this->lang->line('button_login')),           
                );
                
                $this->construct_template($data, 'pages/account/login', 'Login');         
            } else {
                // The form has been submitted.
                $email_address = $this->input->post('username');
                $password = $this->input->post('password');
                    
                $perform_login = $this->dove_auth->login($email_address, $password);
                    
                if($perform_login == true)
                {
                    $last_login = $this->session->userdata('last_login');
                    
                    if(!$last_login)
                    {
                        // The user has never logged in.
                        $this->function->success_message(sprintf($this->lang->line('message_logged_in_never'), $this->session->userdata('username')));
                        redirect('/');
                    } else {
                        $this->function->success_message(sprintf($this->lang->line('message_logged_in_again'), $this->session->userdata('username'), $this->function->convert_time($this->session->userdata('last_login'))));
                        redirect('/');
                    }
                    
                } else {
                    // There user failed to login, redirect them with a message.
                    $this->function->error_message($this->lang->line('error_login_failed'));
                    redirect('/account/login/');
                }                            
            }
        }
    }
    
    public function register()
    {
        // Store the referer.
        $this->function->store_referer();
        
        if($this->session->userdata('logged_in'))
        {
            // The user is logged in, redirect with a message.
            $this->function->error_message($this->lang->line('error_logged_in'));
                
            // Redirect.
            redirect($this->function->refered_from());
        } else {
                
            // Validation rules
            $this->form_validation->set_rules($this->validation_rules['register']);
                
            if($this->form_validation->run() == FALSE)
            {       
                // Data array.
                $data = array(
                    'form_open' => form_open(''.site_url().'/account/register/'),
                    'form_close' => form_close(), 
                    'register_fieldset' => form_fieldset('Register'),
                    'close_fieldset' => form_fieldset_close(),  
                    // Username.
                    'username_label' => form_label($this->lang->line('label_username')),
                    'username_field' => form_input($this->form_fields['register']['0'], set_value($this->form_fields['register']['0']['name'])),
                    'username_field_error' => form_error($this->form_fields['register']['0']['name'], '<div class="error">', '</div>'),
                    // Password.
                    'password_label' => form_label($this->lang->line('label_password')),
                    'password_field' => form_input($this->form_fields['register']['1'], set_value($this->form_fields['register']['1']['name'])),
                    'password_field_error' => form_error($this->form_fields['register']['1']['name'], '<div class="error">', '</div>'),
                    // Confirm Password.
                    'confirm_password_label' => form_label($this->lang->line('label_confirm_password')),
                    'confirm_password_field' => form_input($this->form_fields['register']['2'], set_value($this->form_fields['register']['2']['name'])),
                    'confirm_password_field_error' => form_error($this->form_fields['register']['2']['name'], '<div class="error">', '</div>'),                    
                    // Email Address.
                    'email_address_label' => form_label($this->lang->line('label_email_address')),
                    'email_address_field' => form_input($this->form_fields['register']['3'], set_value($this->form_fields['register']['3']['name'])),
                    'email_address_field_error' => form_error($this->form_fields['register']['3']['name'], '<div class="error">', '</div>'),                      
                    //Confirm Email Address.
                    'confirm_email_address_label' => form_label($this->lang->line('label_confirm_email_address')),
                    'confirm_email_address_field' => form_input($this->form_fields['register']['4'], set_value($this->form_fields['register']['4']['name'])),
                    'confirm_email_address_field_error' => form_error($this->form_fields['register']['4']['name'], '<div class="error">', '</div>'),
                    // Hidden
                    'user_group_field' => form_hidden('group_id', $this->settings->get_setting('default_user_group')) ,                      
                    // Buttons.
                    'register_button' => form_submit(array( 'name' => 'submit', 'class' => 'button blue light'), $this->lang->line('button_register')),                                            
                );
                
                $this->construct_template($data, 'pages/account/register', 'Register'); 
            }else{
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $email_address = $this->input->post('email_address');

                $perform_registration = $this->dove_auth->register($username, $password, $email_address);
                
                print_r($perform_registration);
                    
                if($perform_registration)
                {
                    // Create the email to send to the user.
                    
                    // Define the template
                    $this->parser->theme('default');
                    
                    // Let`s add some config.
                    $config['show'] = false;
                    
                    $subject = 'Account Activation';
                    $message = $this->parser->parse('email/account_activation', $perform_registration, $config);
                    
                    $send_email = $this->function->send_user_email($this->input->post('email_address'), $message, $subject);
                    
                    if($send_email)
                    {
                        // The users account has being created.
                        $this->function->success_message(sprintf($this->lang->line('message_registration_success'), $this->input->post('username')));
                        redirect('/');                        
                    } else {
                        // There user failed to login, redirect them with a message.
                        $this->function->error_message($this->lang->line('error_registration_failed'));
                        redirect('/');                        
                    }
                } else {
                    // There user failed to login, redirect them with a message.
                    $this->function->error_message($this->lang->line('error_registration_failed'));
                    redirect('/');
                }     
            }
        }
    }
    
    public function profile($username)
    {
        echo 'profile';
    }
    
    public function manage()
    {
        
    }
    
    public function activate($activation_code, $username)
    {
        if($this->dove_auth->activate($activation_code, $username) == true)
        {
            $this->function->success_message(sprintf($this->lang->line('message_account_activated'), $username));
            redirect('/');
        } else {
            $this->function->error_message(sprintf($this->lang->line('error_account_activation'), $username));
            redirect('/');
        }        
    }
    
    public function logout()
    {
        // Store the username before the sessions gets destroyed.
        $username = $this->session->userdata('username');
        
        if($this->dove_auth->logout() == true)
        {
            $this->function->success_message(sprintf($this->lang->line('message_logged_out'), $username));
            redirect('/');
        }        
    }
    
    public function username_check($str)
    {
        // Lets check the username make sure its not taken.
        return $this->account->username_check($str);
    }
    
    public function email_check($str)
    {
        // Lets check the email address to make sure they have no account.
        return $this->account->email_check($str);
    }
}