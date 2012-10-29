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
    
    public function get_forums()
    {
        // Setup the select.
        $this->db->select('
            id,
            title,
            permalink,
            created_by,
            created_date,
            last_post_by,
            last_post_date,
            type,
            status,
            visibility,
            parent,
            order,
            tags,
            sticky
        ');
        
        // Setup some options.
        $options = array(
            'status' => 'open',
            'visibility' => 'public',
        );
        
        // Perform the query.
        $query = $this->db->get_where('forums', $options);
        
        // Results.
        foreach($query->result_array() as $row)
        {
            $data[] = array(
                'id' => $row['id'],
                'title' => $row['title'],
            );
        }
        
        return $data;
    }
}