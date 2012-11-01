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
    );

	public function __construct()
	{
		parent::__construct();	
	}
    
    public function topic($forum_permalink, $thread_permalink)
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
            $data['posts'][] = array(
                'id' => $row['id'],
                'forum_id' => $row['forum_id'],
                'thread_id' => $row['thread_id'],
                'content' => $row['content'],
                'created_by' => $row['created_by'],
                'created_date' => $row['created_date'],
                'avatar' => img($this->gravatar->get_gravatar($row['email'])),
                'username' => $row['username'],
                // Post Permalink
                'post_permalink' => anchor(''.site_url().'/topic/'.$forum_permalink.'/'.$thread_permalink.'/#'.$row['id'].'', '#'.$row['id'].''),
            );
        }
        
        // Get the thread information.
        $thread_info = $this->threads->get_thread_info($thread_id);
        
        $this->form_validation->set_rules($this->validation_rules['reply']);
        
        if($this->form_validation->run() == FALSE)
        {
            
            $data = array(
                'thread_name' => $thread_name,
                'posts' => $data['posts'],
                'forum_name' => anchor(''.site_url().'/forums/'.$forum_permalink.'/', $forum_name),
                'post_count' => $this->posts->count_thread_posts($thread_id),
                'thread_last_post_by' => $thread_info['last_post_by'],
                'thread_last_activity' => $this->function->convert_time(strtotime($thread_info['last_activity'])),
                'pagination' => $links,
                'logged_in' => $this->dove_auth->logged_in(),
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
                redirect(''.site_url().'/topic/'.$forum_permalink.'/'.$thread_permalink.'/#'.$enter_reply.'');                
            }
        }
    }
}