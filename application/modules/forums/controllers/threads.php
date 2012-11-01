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

class threads extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}
    
    public function forum($permalink)
    {     
        // Get the forum name from the permalink.
        $forum_name = $this->forums->get_name_from_permalink($permalink);
        
        // Get the forum ID from the permalink.
        $forum_id = $this->forums->get_id_from_permlink($permalink);
        
        // Get all the threads for the forum.
        $threads = $this->threads->get_forum_threads($forum_id);
        
        if(!$threads)
        {
            $data['threads'] = '';
            $has_threads = 'false';
        } else {
            
            $has_threads = 'true';
            
            foreach($threads as $row)
            {
                $data['threads'][] = array(
                    'title' => anchor(''.site_url().'/topic/'.$permalink.'/'.$row['permalink'].'/', $row['title']),
                    'started_by' => anchor(''.site_url().'/account/profile/'.$row['started_by'].'/', $row['started_by']),
                    'post_count' => $this->posts->count_thread_posts($row['id']),
                    'last_activity' => $this->function->convert_time(strtotime($row['last_activity'])),
                    'last_post_by' => anchor(''.site_url().'/account/profile/'.$row['started_by'].'/', $row['last_post_by']),
                );
            }
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
            'forum_last_post_activity' => $this->function->convert_time(strtotime($forum_info['last_post_date'])),
            'has_threads' => $has_threads,
        );
        
        $this->construct_template($data, 'pages/forums/threads', 'Forum: '.$forum_name.'');
    }
}