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