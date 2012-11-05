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

class forums_m extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
    
    public function get_categories()
    {
        // Setup the select.
        $this->db->select('
            id,
            title,
            permalink,
            visibility,
            type,
            order,
        ');
        
        // Setup some options.
        $options = array(
            'visibility' => 'public',
            'type' => 'category',
        );
        
        // Perform the query.
        $query = $this->db->get_where('forums', $options);
        
        // Results.
        foreach($query->result_array() as $row)
        {
            $data[] = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'permalink' => $row['permalink'],
                'visibility' => $row['visibility'],
                'order' => $row['order'],
            );
        }
        
        return $data;
    }
    
    public function get_forums($parent_id)
    {
        // Set the select.
        $this->db->select('
            forums.id,
            forums.title,
            forums.content,
            forums.permalink,
            forums.visibility,
            forums.type,
            forums.order,
            forums.parent,
            forums.last_post_by,
            forums.last_post_date,
            users.username,
            users.email,
        ');

        // Set the join.
        $this->db->join('users', 'users.username = forums.last_post_by');
        
         // Setup some options.
        $options = array(
            'visibility' => 'public',
            'type' => 'forum',
            'parent' => $parent_id,
        );
        
        // Perform the query.
        $query = $this->db->get_where('forums', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $data[] = array(
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'content' => $row['content'],
                    'permalink' => $row['permalink'],
                    'parent' => $row['parent'],
                    'last_post_by' => $row['last_post_by'],
                    'last_post_date' => $row['last_post_date'],
                    'username' => $row['username'],
                    'email' => $row['email'],
                );
            }
        } else {
            return false;
        }
        
        return $data;
    }
    
    public function get_forum_info($forum_id)
    {
        // Set the select.
        $this->db->select('
            last_post_by,
            last_post_date,
        ');
        
        // Set some options.
        $options = array(
            'id' => $forum_id,
        );
        
        // Perforum the query.
        $query = $this->db->get_where('forums', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            foreach($query->result_array() as $row)
            {
                $data = array(
                    'last_post_by' => $row['last_post_by'],
                    'last_post_date' => $row['last_post_date'],
                );
            }
            
            return $data;
        } else {
            return false;
        }
    }
    
    public function get_name_from_permalink($permalink)
    {
        // Set the select.
        $this->db->select('title');
        
        // Set some options.
        $options = array(
            'permalink' => $permalink,
        );
        
        // Perform the query.
        $query = $this->db->get_where('forums', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            return $query->row('title');
        } else {
            return false;
        }
    }
    
    public function get_id_from_permlink($permalink)
    {
        // Set the select.
        $this->db->select('id');
        
        // Set some options.
        $options = array(
            'permalink' => $permalink,
        );
        
        // Perform the query.
        $query = $this->db->get_where('forums', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            return $query->row('id');
        } else {
            return false;
        }
    }
    
    public function get_permalink_by_id($forum_id)
    {
        // Set the select.
        $this->db->select('permalink');
        
        // Set some options.
        $options = array(
            'id' => $forum_id,
        );
        
        // Perform the query.
        $query = $this->db->get_where('forums', $options);
        
        // Results.
        if($query->num_rows() > 0)
        {
            return $query->row('permalink');
        } else {
            return false;
        }
    }
}