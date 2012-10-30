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

class posts_m extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
    
    public function get_thread_posts($thread_id)
    {
        // Set the select.
        $this->db->select('
            posts.id,
            posts.forum_id,
            posts.thread_id,
            posts.content,
            posts.created_by,
            posts.created_date,
            posts.status,
            posts.visibility,
            users.username,
            users.email,
        ');
        
        // Set the join.
        $this->db->join('users', 'users.username = posts.created_by');
        
        // Set some options.
        $options = array(
            'thread_id' => $thread_id,
            'visibility' => 'public',
            'status' => 'open',
        );
        
        // Set the order.
        $this->db->order_by('created_date', 'desc');
        
        // Perform the query.
        $query = $this->db->get_where('posts', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $data[] = array(
                    'id' => $row['id'],
                    'forum_id' => $row['forum_id'],
                    'thread_id' => $row['thread_id'],
                    'content' => $row['content'],
                    'created_by' => $row['created_by'],
                    'created_date' => $row['created_date'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                );
            }
            
            return $data;
        } else {
            return false;
        }
    }
    
    public function get_first_thread_post($thread_id)
    {
        // Set the select.
        $this->db->select('
            posts.id,
            posts.forum_id,
            posts.thread_id,
            posts.content,
            posts.created_by,
            posts.created_date,
            posts.status,
            posts.visibility,
            users.username,
            users.email,
        ');
        
        // Set the join.
        $this->db->join('users', 'users.username = posts.created_by');
        
        // Set some options.
        $options = array(
            'thread_id' => $thread_id,
            'visibility' => 'public',
            'status' => 'open',
        );
        
        // Set the limit.
        $this->db->limit('1');
        
        // Order By.
        $this->db->order_by('created_date', 'desc');
        
        // Perform the query.
        $query = $this->db->get_where('posts', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $data = array(
                    'id' => $row['id'],
                    'forum_id' => $row['forum_id'],
                    'thread_id' => $row['thread_id'],
                    'content' => $row['content'],
                    'created_by' => $row['created_by'],
                    'created_date' => $row['created_date'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                );
            }
            
            return $data;
        } else {
            return false;
        }
    }
    
    public function count_forum_posts($forum_id)
    {
        // Set some options.
        $options = array(
            'forum_id' => $forum_id,
        );
        
        // Perform the query.
        $query = $this->db->get_where('posts', $options);
        
        // Results.
        if($query->num_rows()  > 0)
        {
            return $query->num_rows();
        } else {
            return '0';
        }
    }
    
    public function count_thread_posts($thread_id)
    {
        // Set some options.
        $options = array(
            'thread_id' => $thread_id,
        );
        
        // Perform the query.
        $query = $this->db->get_where('posts', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            return $query->num_rows();
        } else {
            return '0';
        }
    }
}