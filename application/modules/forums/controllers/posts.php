<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
 * @link http://www.doveforums.com
 * @since v 2.0.0
 * @author Christopher Baines
 * 
 */

class posts extends MY_Controller {
    
    // Validation data.
    private $validation_rules = array(
        'reply' => array(
            //0
            array(
                'field' => 'body',
                'label' => 'lang:rules_label_body',
                'rules' => 'required',
            ),
        ),
        'edit' => array(
            //0
            array(
                'field' => 'body',
                'label' => 'lang:rules_label_body',
                'rules' => 'required',
            ),
        ),
    );
    
    // Form fields.
    private $form_fields = array(
        'reply' => array(
            //0
            array(
                'id' => 'reply_body',
                'name' => 'body',
                'cols' => '20',
            ),
            //1
            array(
                'id' => 'tags',
                'name' => 'tags',
                'class' => 'text',
            ),
        ),
        
        'edit' => array(
            //0
            array(
                'id' => 'reply_body',
                'name' => 'body',
                'cols' => '20',
            ),
            //1
            array(
                'id' => 'tags',
                'name'=> 'tags',
                'class' => 'text',
            ),
            //2
            array(
                'id' => 'reason',
                'name' => 'reason',
                'class' => 'text',
            ),
        ),
    );

	public function __construct()
	{
		parent::__construct();	
	}
    
    public function topic($forum_permalink, $thread_permalink, $limit=NULL, $offset=NULL)
    {       
        
        // Get the thread name from the permalink.
        $thread_name = $this->threads->get_name_from_permalink($thread_permalink);
        
        // Get forum name from the permalink.
        $forum_name = $this->forums->get_name_from_permalink($forum_permalink);
        
        // Get the thread ID from the permalink.
        $thread_id = $this->threads->get_id_from_permalink($thread_permalink);
        
  		/**
  		* Setup config settings for pagination
  		*
  		* @base_url - The base url for the pagination.
  		* @total_rows - The total number of returned rows.
  		* @url_segment - Part of url to look at for pagination offset.
        * @per_page - Setting for topics to show per page.
  		**/
  		$config['base_url'] = site_url().'/topic/'.$forum_permalink.'/'.$thread_permalink.'/';
  		$config['total_rows'] = $this->posts->count_thread_posts($thread_id); 
  		$config['uri_segment'] = 4;
  		$config['per_page'] = $this->settings->get_setting('posts_per_page');
        $config['num_links'] = 2;
        
  		/**
  		* Initialize the pagination
  		**/
  		$this->pagination->initialize($config);
    
  		/**
  		* Build links for the pagination
  		**/		
  		$links = $this->pagination->create_links();
            
        /**
        * Offset & Limit for topics query
        **/
   	   	$limit = $this->settings->get_setting('posts_per_page');
   	   	$offset = $this->uri->segment(4); // For pagination
        
        // Get all the posts in the thread.
        $posts = $this->posts->get_thread_posts($thread_id, $limit, $offset);
        
        // Get only the first post from the array.
        $first_post = $this->posts->get_first_thread_post($thread_id);
                
        // Loop though remaining posts.
        foreach($posts as $row)
        {
            // Build the links.
            if($row['created_by'] == $this->session->userdata('username') || $this->dove_auth->is_admin())
            {
                // The user may edit and delete this.
                $edit_link = anchor(''.site_url().'/edit_post/'.$row['id'].'/', 'Edit');
                $delete_link = anchor(''.site_url().'/delete_post/'.$row['id'].'/', 'Delete');
                
            } else {
                
                $edit_link = '';
                $delete_link = '';
            }
            
            $data['posts'][] = array(
                'id' => $row['id'],
                'forum_id' => $row['forum_id'],
                'thread_id' => $row['thread_id'],
                'content' => $row['content'],
                'created_by' => anchor(''.site_url().'/account/profile/'.$row['username'].'/', $row['username'], 'title="View '.$row['username'].'`s profile"'),
                'created_date' => $row['created_date'],
                'avatar' => img($this->gravatar->get_gravatar($row['email'])),
                'username' => $row['username'],
                // Post Permalink
                'post_permalink' => anchor(''.site_url().'/topic/'.$forum_permalink.'/'.$thread_permalink.'/'.$limit.'/'.$offset.'/#'.$row['id'].'', '#'.$row['id'].'', 'title="Permalink"'),
                // Links
                'edit_link' => $edit_link,
                'delete_link' => $delete_link,
                'spam_link' => anchor(''.site_url().'/spam_post/'.$row['id'].'/', 'Spam'),
            );
        }
        
        // Get the thread information.
        $thread_info = $this->threads->get_thread_info($thread_id);
        
        if($thread_info['type'] == 'sticky')
        {
            $stick_thread = anchor(''.site_url().'/unstick/'.$thread_permalink.'', 'Unstick Thread');
        } else {
            $stick_thread = anchor(''.site_url().'/stick/'.$thread_permalink.'', 'Stick Thread');
        }
        
        if($thread_info['status'] == 'open')
        {
            $thread_status = anchor(''.site_url().'/close/'.$thread_permalink.'', 'Close Thread');
            $thread_open = true;
        } else {
            $thread_status = anchor(''.site_url().'/open/'.$thread_permalink.'', 'Open Thread');
            $thread_open = false;
        }
        
        $this->form_validation->set_rules($this->validation_rules['reply']);
        
        if($this->form_validation->run() == FALSE)
        {
            
            $data = array(
                'thread_name' => $thread_name,
                'posts' => $data['posts'],
                'forum_name' => anchor(''.site_url().'/forums/'.$forum_permalink.'/', $forum_name),
                'post_count' => $this->posts->count_thread_posts($thread_id),
                'thread_last_post_by' => anchor(''.site_url().'/account/profile/'.$thread_info['last_post_by'].'/', $thread_info['last_post_by'], 'title="View '.$thread_info['last_post_by'].'`s profile"'),
                'thread_last_activity' => $this->function->convert_time(strtotime($thread_info['last_activity'])),
                'pagination' => $links,
                'logged_in' => $this->dove_auth->logged_in(),
                'is_admin' => $this->dove_auth->is_admin(),
                'stick_thread' => $stick_thread,
                'thread_status' => $thread_status,
                'thread_open' => $thread_open,
                'posted' => $row['created_date'],
                // Create Reply 
                'form_open' => form_open(''.site_url().'/topic/'.$forum_permalink.'/'.$thread_permalink.'/'),
                'form_close' => form_close(),
                'create_discussion_fieldset' => form_fieldset('Reply to `'.$thread_name.'`'),
                'close_fieldset' => form_fieldset_close(),
                // body
                'body_label' => form_label($this->lang->line('label_reply')),
                'body_field' => form_textarea($this->form_fields['reply']['0'], set_value($this->form_fields['reply']['0']['name'])),
                'body_field_error' => form_error($this->form_fields['reply']['0']['name'], '<div class="error">', '</div>'),
                // tags
                'tags_label' => form_label($this->lang->line('label_tags')),
                'tags_field' => form_input($this->form_fields['reply']['1'], set_value($this->form_fields['reply']['1']['name'])),
                // Buttons
                'submit_button' => form_submit(array( 'name' => 'submit', 'class' => 'button blue'), $this->lang->line('button_submit_thread')),  
            );
            
            $this->construct_template($data, 'pages/forums/posts', 'Thread: '.$thread_name.'');
        } else {
            
            $enter_reply = $this->posts->reply($forum_permalink, $thread_permalink);
            
            if($enter_reply == false)
            {
                // There has been a problem, create a message and redirect.
                $this->function->error_message($this->lang->line('error_create_reply'));
                redirect(''.site_url().'/topic/'.$forum_permalink.'/'.$thread_permalink.'');
            } else {
                // Create a success message and redirect the user.
                redirect(''.site_url().'/topic/'.$forum_permalink.'/'.$thread_permalink.'/'.$limit.'/'.$offset.'/#'.$enter_reply.'');                
            }
        }
    }
    
    public function edit($post_id)
    {
        // Store the page the user came from.
        $this->function->store_referer();
        
        // Set the validation rules.
        $this->form_validation->set_rules($this->validation_rules['edit']);
        
        // Check if the form has been submitted.
        if($this->form_validation->run() == FALSE)
        {
            // Get the post information.
            $post = $this->posts->get_post($post_id);
            
            $data = array(
                'form_open' => form_open(''.site_url().'/edit_post/'.$post_id.'/'),
                'form_close' => form_close(),
                'edit_post_fieldset' => form_fieldset('Edit Reply'),
                'revision_fieldset' => form_fieldset('Revision'),
                'close_fieldset' => form_fieldset_close(),
                // body
                'body_label' => form_label($this->lang->line('label_reply')),
                'body_field' => form_textarea($this->form_fields['edit']['0'], set_value($this->form_fields['edit']['0']['name'], $post['content'])),
                'body_field_error' => form_error($this->form_fields['edit']['0']['name'], '<div class="error">', '</div>'),
                // tags
                'tags_label' => form_label($this->lang->line('label_tags')),
                'tags_field' => form_input($this->form_fields['edit']['1'], set_value($this->form_fields['edit']['1']['name'], $post['tags'])),
                // Reason
                'reason_label' => form_label($this->lang->line('label_reason')),
                'reason_field' => form_input($this->form_fields['edit']['2'], set_value($this->form_fields['edit']['2']['name'])),
                // Buttons
                'submit_button' => form_submit(array( 'name' => 'submit', 'class' => 'button blue'), $this->lang->line('button_submit_thread')),  
            );
            
            $this->construct_template($data, 'pages/forums/edit_post', 'Edit Reply');
            
        } else {
            $edit = $this->posts->edit($post_id);
            
            if($edit == true)
            {
                redirect($this->function->refered_from());
            } else {
                $this->function->error_message($this->lang->line('error_edit_post'));
                redirect($this->function->refered_from());                
            }
        }
    }
    
    public function delete($post_id)
    {
        // Store the page the user came from.
        $this->function->store_referer();
        
        $delete = $this->posts->delete($post_id);
        
        if($delete == true)
        {
            redirect($this->function->refered_from());
        } else {
            $this->function->error_message($this->lang->line('error_delete_post'));
            redirect($this->function->refered_from());
        }
    }
    
    public function spam($post_id)
    {
        // Store the page the user came from.
        $this->function->store_referer();
        
        $spam = $this->posts->spam($post_id);
        
        if($spam == true)
        {
            redirect($this->function->refered_from());
        } else {
            $this->function->error_message($this->lang->line('error_spam_post'));
            redirect($this->function->refered_from());
        }
    }
}