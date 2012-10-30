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

class forums extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
    
    public function index()
    {
        // Get all the parent forums.
        $categories = $this->forums->get_categories();
        
        foreach($categories as $row)
        {
            $data['categories'][] = array(
                'id' => $row['id'],
                'title' => $row['title'],
            );
            
            $forums = $this->forums->get_forums($row['id']);
            
            if($forums)
            {
                foreach($forums as $row)
                {
                    $data['forums_'.$row['parent']][] = array(
                        'forum_id' => $row['id'],
                        'forum_title' => anchor(''.site_url().'/forums/'.$row['permalink'].'/', $row['title']),
                        'forum_content' => $row['content'],
                        'forum_last_post' => anchor(''.site_url().'/account/profile/'.$row['last_post_by'].'', $row['username']),
                        'forum_last_post_date' => $row['last_post_date'],
                        'post_count' => $this->posts->count_forum_posts($row['id']),
                        'thread_count' => $this->threads->count_forum_threads($row['id']),
                    );
                }
            }
        }

        $this->construct_template($data, 'pages/forums/home', 'Home Page');
    }
    
    public function view($permalink)
    {     
        // Get the forum name from the permalink.
        $forum_name = $this->forums->get_name_from_permalink($permalink);
        
        // Get the forum ID from the permalink.
        $forum_id = $this->forums->get_id_from_permlink($permalink);
        
        // Get all the threads for the forum.
        $threads = $this->threads->get_forum_threads($forum_id);
        
        foreach($threads as $row)
        {
            $data['threads'][] = array(
                'title' => anchor(''.site_url().'/topic/'.$permalink.'/'.$row['permalink'].'/', $row['title']),
                'started_by' => anchor(''.site_url().'/account/profile/'.$row['started_by'].'/', $row['started_by']),
                'post_count' => $this->posts->count_thread_posts($row['id']),
                'last_activity' => $row['last_activity'],
                'last_post_by' => anchor(''.site_url().'/account/profile/'.$row['started_by'].'/', $row['last_post_by']),
                
            );
        }
        
        // Forum Data.
        $forum_info = $this->forums->get_forum_info($forum_id);
        
        $data = array(
            'forum_name' => $forum_name,
            'threads' => $data['threads'],
            // Forum Info
            'forum_post_count' => $this->posts->count_forum_posts($forum_id),
            'forum_thread_count' => $this->threads->count_forum_threads($forum_id),
            'forum_last_post_by' => anchor(''.site_url().'/account/profile/'.$forum_info['last_post_by'].'/', $forum_info['last_post_by']),
            'forum_last_post_activity' => $forum_info['last_post_date'],
        );
        
        $this->construct_template($data, 'pages/forums/threads', 'Forum: '.$forum_name.'');
    }
    
    public function topic($forum_permalink, $thread_permalink)
    {       
        // Get the thread name from the permalink.
        $thread_name = $this->threads->get_name_from_permalink($thread_permalink);
        
        // Get forum name from the permalink.
        $forum_name = $this->forums->get_name_from_permalink($forum_permalink);
        
        // Get the thread ID from the permalink.
        $thread_id = $this->threads->get_id_from_permalink($thread_permalink);
        
        // Get all the posts in the thread.
        $posts = $this->posts->get_thread_posts($thread_id);
        
        // Get only the first post from the array.
        $first_post = array_shift($posts);
                
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
            );
        }
        
        $data = array(
            'first_post_content' => $first_post['content'],
            'first_post_username' => $first_post['username'],
            'first_post_avatar' => img($this->gravatar->get_gravatar($first_post['email'])),
            'thread_name' => $thread_name,
            'posts' => $data['posts'],
            'forum_name' => anchor(''.site_url().'/forums/'.$forum_permalink.'/', $forum_name),
            'post_count' => $this->posts->count_thread_posts($thread_id),
        );
        
        $this->construct_template($data, 'pages/forums/posts', 'Thread: '.$thread_name.'');
    }
}