<?php

/**
 * ServersGroups
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class ServersGroups extends BaseServersGroups
{

	/**
	 * grid
	 * create the configuration of the grid
	 */	
	public static function grid($rowNum = 10) {
		
		$translator = Zend_Registry::getInstance ()->Zend_Translate;
		
		$config ['datagrid'] ['columns'] [] = array ('label' => null, 'field' => 'group_id', 'alias' => 'group_id', 'type' => 'selectall' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'ID' ), 'field' => 'group_id', 'alias' => 'group_id', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Name' ), 'field' => 'name', 'alias' => 'name', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['fields'] = "group_id, name as name";
		
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()->select ( $config ['datagrid'] ['fields'] )->from ( 'ServersGroups sg' );
		
		$config ['datagrid'] ['rownum'] = $rowNum;
		
		$config ['datagrid'] ['basepath'] = "/admin/serversgroups/";
		$config ['datagrid'] ['index'] = "group_id";
		$config ['datagrid'] ['rowlist'] = array ('10', '50', '100', '1000' );
		
		$config ['datagrid'] ['buttons'] ['edit'] ['label'] = $translator->translate ( 'Edit' );
		$config ['datagrid'] ['buttons'] ['edit'] ['cssicon'] = "edit";
		$config ['datagrid'] ['buttons'] ['edit'] ['action'] = "/admin/serversgroups/edit/id/%d";
		
		$config ['datagrid'] ['buttons'] ['delete'] ['label'] = $translator->translate ( 'Delete' );
		$config ['datagrid'] ['buttons'] ['delete'] ['cssicon'] = "delete";
		$config ['datagrid'] ['buttons'] ['delete'] ['action'] = "/admin/serversgroups/delete/id/%d";
		return $config;
	}

    /**
     * getAllInfo
     * Get a record by ID
     * @param $id
     * @return ARRAY Record
     */
    public static function getAllInfo($id, $fields = "*", $language_id = 1) {
        try {
            $dq = Doctrine_Query::create ()
					            ->from ( 'ServersGroups sg' )
					            ->where ( 'sg.group_id = ?', $id );
					            
            if($fields != "*"){
            	$dq->select($fields);
            }
            
            $hp = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
            
            if (isset ( $hp [0] )) {
                return $hp [0];
            } else {
                return array ();
            }
        
        } catch ( Exception $e ) {
            die ( $e->getMessage () );
        }
    }   


	/*
	 * find
	 * Find an item by its id
	 */
	public static function find($id){
		return Doctrine::getTable ( 'ServersGroups' )->find ( $id );
	}


    /**
     * getAttributes
     * Get all the attributes within the selected group
     * @param $id
     * @return ARRAY Record
     */
    public static function getServers($id) {
        try {
        	$servers = array();
            $records = Doctrine_Query::create ()
            ->select('server_id')
            ->from ( 'ServersGroupsIndexes p' )
            ->where ( 'p.group_id = ?', $id )
            ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
            
            
	        foreach ( $records as $c ) {
				$servers [] = $c ['server_id'];
			}
			
            return $servers;
        
        } catch ( Exception $e ) {
            die ( $e->getMessage () );
        }
    }   



	/**
	 * getList
	 * Get a list ready for the html select object
	 * @return array
	 */
	public static function getList($empty = false) {
		$items = array ();
		$arrTypes = Doctrine::getTable ( 'ServersGroups' )->findAll ();
	
		$translator = Zend_Registry::getInstance ()->Zend_Translate;
		
		if ($empty) {
			$items [0] = '';
		}
		
		foreach ( $arrTypes->getData () as $c ) {
			$items [$c ['group_id']] = $c ['name'];
		}
		
		return $items;
	}


}