<?php

/**
 * TicketsCategories
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TicketsCategories extends BaseTicketsCategories {
	
	/**
	 * Grid configuration
	 */
	public static function grid($rowNum = 10) {
	
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
	
		$config ['datagrid'] ['columns'] [] = array ('label' => null, 'field' => 'category_id', 'alias' => 'category_id', 'type' => 'selectall' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Id' ), 'field' => 'category_id', 'alias' => 'category_id', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Category' ), 'field' => 'category', 'alias' => 'category', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
	
		$config ['datagrid'] ['fields'] = "category_id, category";
		$config ['datagrid'] ['rownum'] = $rowNum;
	
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()->select ( $config ['datagrid'] ['fields'] )->from ( 'TicketsCategories tc' );
	
		$config ['datagrid'] ['basepath'] = "/admin/ticketscategories/";
		$config ['datagrid'] ['index'] = "category_id";
	
		return $config;
	}
	
	
	/**
	 * Get a record by ID
	 * @param $id
	 * @return Doctrine Record
	 */
	public static function find($id, $fields = "*", $retarray = false) {
		$dq = Doctrine_Query::create ()->select ( $fields )->from ( 'TicketsCategories tc' )
		->where ( "category_id = ?", $id )->limit ( 1 );
	
		$retarray = $retarray ? Doctrine_Core::HYDRATE_ARRAY : null;
		$records = $dq->execute ( array (), $retarray );
		return $records;
	}
	
	/**
	 * Get all data
	 * @param $id
	 * @return Doctrine Record / Array
	 */
	public static function getAllInfo($id, $fields = "*", $retarray = false) {
	
		try {
			$dq = Doctrine_Query::create ()->select ( $fields )
			->from ( 'TicketsCategories tc' )
			->where ( "category_id = ?", $id )
			->limit ( 1 );
	
			$retarray = $retarray ? Doctrine_Core::HYDRATE_ARRAY : null;
			$item = $dq->execute ( array (), $retarray );
	
			return $item;
		} catch (Exception $e) {
			die ( $e->getMessage () );
		}
	}
	
	public static function getList() {
		$items = array ();
		$data = Doctrine_Query::create ()
						->select ( "category_id, category" )
						->from ( 'TicketsCategories tc' )
						->orderBy('category asc')
						->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
		
		foreach ( $data as $c ) {
			$items [$c ['category_id']] = $c ['category'];
		}
		return $items;
	}
	
	/**
	 * get the category name by id
	 */
	public static function getName($id) {
		$data = Doctrine_Query::create ()
						->select ( "category" )
						->from ( 'TicketsCategories tc' )
						->where('category_id = ?', $id)
						->limit(1)
						->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
		
		return !empty($data[0]['category']) ? $data[0]['category'] : NULL;

	}
}