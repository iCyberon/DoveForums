<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

class dove_acl {

    var $perms = array();
    var $userID;
    var $userRoles = array();
    
    var $ci;
    
    /**
     * dove_acl::__construct()
     * 
     * @param mixed $config
     * @return
     */
    function __construct($config=array())
    {
        $this->ci = &get_instance();
        
        $this->userID = floatval($config['userID']);
        $this->userRoles = $this->getUserRoles();
        $this->buildACL();
    } 
    
    /**
     * dove_acl::buildACL()
     * 
     * @return
     */
    function buildACL()
    {
        // First, Get the rules for the users role
        if(count($this->userRoles) > 0)
        {
            $this->perms = array_merge($this->perms, $this->getRolePerms($this->userRoles));
        }
        
        // Then, Get the individual user permissions
        $this->perms = array_merge($this->perms, $this->getUserPerms($this->userID));
    }
    
    /**
     * dove_acl::getPermKeyFromID()
     * 
     * @param mixed $permID
     * @return
     */
    public function getPermKeyFromID($permID)
    {
        $this->ci->db->select('permKey')
                        ->where('id', floatval($permID));
        $query = $this->ci->db->get('perm_data', 1);
        $data = $query->result();
        return $data[0]->permKey;
    }
    
    /**
     * dove_acl::getPermNameFromID()
     * 
     * @param mixed $permID
     * @return
     */
    public function getPermNameFromID($permID)
    {
        $this->ci->db->select('permName')
                        ->where('id', floatval($permID));
        $query = $this->ci->db->get('perm_data', 1);
        $data = $query->result();
        return $data[0]->permName;
    }
    
    /**
     * dove_acl::getRoleNameFromID()
     * 
     * @param mixed $roleID
     * @return
     */
    public function getRoleNameFromID($roleID)
    {
        $this->ci->db->select('name')
                        ->where('id', floatval($roleID),1);
        $query = $this->ci->db->get('groups');
        $data = $query->result();
        return $data[0]->name;
    }
    
    /**
     * dove_acl::getUserRoles()
     * 
     * @return
     */
    public function getUserRoles() 
    {
        $this->ci->db->where(array('userID' => floatval($this->userID)))
                        ->order_by('addDate', 'asc');
        $query = $this->ci->db->get('user_roles');
        $data = $query->result();
        
        $resp = array();
        foreach($data as $row)
        {
            $resp[] = $row->roleID;
        }

        return $resp;
    }
    
    /**
     * dove_acl::getAllRoles()
     * 
     * @param string $format
     * @return
     */
    public function getAllRoles($format='ids')
    {
        $format =strtolower($format);
        
        $this->ci->db->order_by('name', 'asc');
        $query = $this->ci->db->get('groups');
        $data = $query->result();
        
        $resp = array();
        foreach($data as $row)
        {
            if($format == 'full')
            {
                $resp[] = array(
                    'id' => $row->id, 
                    'name' => $row->name
                );
            } else {
                $resp[] = $row->id;
            }
        }
        return $resp;
    }
    
    /**
     * dove_acl::getAllPerms()
     * 
     * @param string $format
     * @return
     */
    public function getAllPerms($format='ids')
    {
        $format = strtolower($format);
        
        $this->ci->db->order_by('ID', 'asc');
        //$this->ci->db->where('type', $type);
        $query = $this->ci->db->get('perm_data');
        $data = $query->result();
        
        $respt = array();
        foreach($data as $row)
        {
            if($format == 'full')
            {
                $resp[$row->permKey] = array('id' => $row->ID, 'name' => $row->permName, 'key' => $row->permKey);
            } else {
                $resp[] = $row->ID;
            }
        }
        return $resp;
    }
    
    /**
     * dove_acl::getRolePerms()
     * 
     * @param mixed $role
     * @return
     */
    public function getRolePerms($role)
    {
        if(is_array($role))
        {
            $this->ci->db->where_in('roleID', $role);
        } else {
            $this->ci->db->where(array('roleID' => floatval($role)));
        }
        
        $this->ci->db->order_by('id', 'asc');
        $query = $this->ci->db->get('role_perms');
        $data = $query->result();
        $perms = array();
        foreach( $data as $row )
        {
            $pK = strtolower($this->getPermKeyFromID($row->permID));
            
            if($pK == '') { continue; }
            if($row->value === '1')
            {
                $hP = true;
            } else {
                $hP = false;
            }
            $perms[$pK] = array(
                'perm' => $pK, 
                'inheritted' => true, 
                'value' => $hP, 
                'name' => $this->getPermNameFromID($row->permID), 
                'id' => $row->permID,
            );
        }
        return $perms;
    }
    
    /**
     * dove_acl::getUserPerms()
     * 
     * @param mixed $userID
     * @return
     */
    public function getUserPerms($userID)
    {
        $this->ci->db->where('userID', floatval($userID))
                        ->order_by('addDate', 'asc');
        $query = $this->ci->db->get('user_perms');
        $data = $query->result();
        
        $perms = array();
        foreach($data as $row)
        {
            $pK = strtolower($this->getPermKeyFromID($row->permID));
            if($pK == ''){ continue; }
            if($row->value == '1')
            {
                $hP = true;
            } else {
                $hP = false;
            }
            $perms[$pK] = array(
                'perm' => $pK, 
                'inheritted' => false, 
                'value' => $hP,
                'name' => $this->getPermNameFromID($row->permID),
                'id' => $row->permID,
            );
        }
        return $perms;
    }
    
    /**
     * dove_acl::hasRole()
     * 
     * @param mixed $roleID
     * @return
     */
    public function hasRole($roleID)
    {
        foreach($this->userRoles as $k => $v)
        {
            if(floatval($v) === floatval($roleID))
            {
                return true;
            }
        }
        return false;
    }
    
    /**
     * dove_acl::hasPermission()
     * 
     * @param mixed $permKey
     * @return
     */
    public function hasPermission($permKey)
    {
        $permKey = strtolower($permKey);
        if(array_key_exists($permKey, $this->perms))
        {
            if($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true)
            {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * dove_acl::permsArray()
     * 
     * @return
     */
    public function permsArray()
    {
        return $this->perms;
    }
}