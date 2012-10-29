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
                        'forum_title' => $row['title'],
                        'forum_content' => $row['content'],
                    );
                }
            }
        }
        
        // Test it works.
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        $this->construct_template($data, 'pages/forums/home', 'Home Page');
    }
}