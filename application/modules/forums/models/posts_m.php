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
    
    public function get_thread_posts($thread_id, $limit=NULL, $offset=NULL)
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
        
        // Set the limit.
        $this->db->limit($limit, $offset);
        
        // Set some options.
        $options = array(
            'thread_id' => $thread_id,
            'visibility' => 'public',
        );
        
        // Set the order.
        $this->db->order_by('created_date', 'asc');
        
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
                    'status' => $row['status'],
                );
            }
            
            return $data;
        } else {
            return false;
        }
    }
    
    public function get_post($post_id)
    {
        // Set the select.
        $this->db->select('
            content,
            tags,
            forum_id,
            thread_id,
        ');
        
        // Set some options.
        $options = array(
            'id' => $post_id,
        );
        
        // Perform the query.
        $query = $this->db->get_where('posts', $options);
        
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $data = array(
                    'content' => $row['content'],
                    'tags' => $row['tags'],
                    'thread_id' => $row['thread_id'],
                    'forum_id' => $row['forum_id'],
                );
            }
            
            return $data;
        } else {
            return false;
        }
    }
    
    public function reply($forum_permalink, $thread_permalink)
    {
        // Get the forum ID.
        $forum_id = $this->forums->get_id_from_permlink($forum_permalink);
        
        // Get the thread ID.
        $thread_id = $this->threads->get_id_from_permalink($thread_permalink);
        
        // Get the post date.
        $data = array(
            'forum_id' => $forum_id,
            'thread_id' => $thread_id,
            'content' => $this->input->post('body'),
            'tags' => $this->input->post('tags'),
            'created_by' => $this->session->userdata('username'),
            'created_date' => date('Y.m.d H.i.s'),
        );
        
        // Insert the data into the posts table.
        $this->db->insert('posts', $data);
        
        // Check to see if it worked.
        if($this->db->affected_rows() > 0)
        {
            // The post has been entered into the database, lets update the threads and forums tables.
            // Get the post insert id.
            $post_id = $this->db->insert_id();
            
            $data = array(
                'last_activity' => date('Y.m.d H.i.s'),
                'last_post_by' => $this->session->userdata('username'),
            );
            
            // Set some options.
            $options = array(
                'id' => $thread_id,
            );
            
            // Perform the update.
            $this->db->update('threads', $data, $options);
            
            if($this->db->affected_rows() > 0)
            {
                $data = array(
                    'last_post_by' => $this->session->userdata('username'),
                    'last_post_date' => date('Y.m.d H.i.s'),
                );
                
                // Set some options.
                $options = array(
                    'id' => $forum_id,
                );
                
                // Perform the update.
                $this->db->update('forums', $data, $options);
                
                if($this->db->affected_rows() > 0)
                {
                    return $post_id;
                } else {
                    return false;
                }
            } else {
                return false;
            }
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
            'visibility' => 'public',
            'status' => 'open',
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
            'visibility' => 'public',
            'status' => 'open',
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
    
    public function edit($post_id)
    {
        // Set some data.
        $data = array(
            'content' => $this->input->post('body'),
            'tags' => $this->input->post('tags'),
            'updated_date' => date('Y.m.d H.i.s'),
            'updated_by' => $this->session->userdata('username'),
            'updated_reason' => $this->input->post('reason'),
        );
        
        // set some options.
        $options = array(
            'id' => $post_id,
        );
        
        // Perform the update.
        $this->db->update('posts', $data, $options);
        
        if($this->db->affected_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
    
    public function delete($post_id)
    {
        // Set some options.
        $options = array(
            'id' => $post_id,
        );
        
        $this->db->delete('posts', $options);
        
        if($this->db->affected_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
    }
    
    public function spam($post_id)
    {
        // Set some data.
        $data = array(
            'status' => 'spam',
            'visibility' => 'hidden',
            'updated_date' => date('Y.m.d H.i.s'),
            'updated_by' => $this->session->userdata('username'),
            'updated_reason' => 'Spam',
        );
        
        // Set some options.
        $options = array(
            'id' => $post_id,
        );
        
        // Perform the update.
        $this->db->update('posts', $data, $options);
        
        if($this->db->affected_rows() > 0)
        {
            return true;
        } else {
            return false;
        }
        
    }
}