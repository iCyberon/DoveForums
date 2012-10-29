<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
 * @link http://doveforums.com
 * @since Version 1.0.4
 * @author Christopher Baines
 * 
 */

/**
* Tables
**/
$config['tables']['groups'] = 'groups';
$config['tables']['users'] = 'users';
$config['tables']['meta'] = 'meta';

/**
* Default Group
**/
$config['default_group'] = 'members';

/**
* Admin Group
**/
$config['admin_group'] = 'admin';

/**
 * Moderator Group
 **/
$config['moderator_group'] = 'moderator';

/**
* Meta table column you want to join with 
**/
$config['join'] = 'user_id';

/**
* Columns in your meta table 
**/
$config['columns'] = array('first_name', 'last_name', 'location', 'user_language', 'twitter', 'facebook');

/**
* The database column that is used to login with.
**/
$config['identity'] = 'username';

/**
* Use email activation for registration ?
**/
$config['email_activation'] = true;

/**
* How long should the user be remembered (seconds)
**/
$config['user_expire'] = 86500;

/**
* Folder where email templates are installed 
* Default: dove_auth/
**/
$config['email_templates'] = 'dove_auth/';

/**
* Salt Length
**/
$config['salt_length'] = 10;