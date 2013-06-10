<?php

/**
 * UrlRewrite
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class UrlRewrite extends BaseUrlRewrite
{
	
	/**
	 * grid
	 * create the configuration of the grid
	 */	
	public static function grid($rowNum = 10) {
		
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		
		$config ['datagrid'] ['columns'] [] = array ('label' => null, 'field' => 'u.url_rewrite_id', 'alias' => 'url_rewrite_id', 'type' => 'selectall' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'ID' ), 'field' => 'u.url_rewrite_id', 'alias' => 'url_rewrite_id', 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Request' ), 'field' => 'request_path', 'alias' => 'request_path', 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Target' ), 'field' => 'target_path', 'alias' => 'target_path', 'searchable' => true, 'type' => 'string' );
		
		$config ['datagrid'] ['fields'] = "url_rewrite_id, target_path, request_path as request_path";
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()->select ( $config ['datagrid'] ['fields'] )->from ( 'UrlRewrite u' );
		
		$config ['datagrid'] ['rownum'] = $rowNum;
		
		$config ['datagrid'] ['basepath'] = "/admin/urlrewrite/";
		$config ['datagrid'] ['index'] = "url_rewrite_id";
		$config ['datagrid'] ['rowlist'] = array ('10', '50', '100', '1000' );
		
		$config ['datagrid'] ['buttons'] ['edit'] ['label'] = $translator->translate ( 'Edit' );
		$config ['datagrid'] ['buttons'] ['edit'] ['cssicon'] = "edit";
		$config ['datagrid'] ['buttons'] ['edit'] ['action'] = "/admin/urlrewrite/edit/id/%d";
		
		$config ['datagrid'] ['buttons'] ['delete'] ['label'] = $translator->translate ( 'Delete' );
		$config ['datagrid'] ['buttons'] ['delete'] ['cssicon'] = "delete";
		$config ['datagrid'] ['buttons'] ['delete'] ['action'] = "/admin/urlrewrite/delete/id/%d";
		return $config;
	}	
	
    /**
     * getAllInfo
     * Get all data with the url rewrite id
     * @param $id
     * @return Doctrine Record / Array
     */
    public static function getAllInfo($id, $fields = "*", $retarray = false) {
        
        try {
            $dq = Doctrine_Query::create ()->select ( $fields )
                    ->from ( 'UrlRewrite u' )
                    ->where ( "url_rewrite_id = $id" )
                    ->limit ( 1 );
            
            $retarray = $retarray ? Doctrine_Core::HYDRATE_ARRAY : null;
            $item = $dq->execute ( array (), $retarray );
            
            return $item;   
        } catch (Exception $e) {
            die ( $e->getMessage () );
        }
    }	

	/**
	 * find
	 * Get a record by ID
	 * @param $id
	 * @return Doctrine Record
	 */
	public static function find($id) {
		return Doctrine::getTable ( 'UrlRewrite' )->find ( $id );
	}
	
	
	/**
	 * getTarget
	 * Get target path by the request path
	 * @param string $uri
	 */
	public static function getTarget($uri) {
		$records = Doctrine_Query::create ()
					->from ( 'UrlRewrite u' )
					->leftJoin ( 'u.Products p' )
					->leftJoin ( 'u.ProductsCategories c' )
					->where ( 'u.request_path like ?', "%$uri%" )
					->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
		
		if(!empty($records[0]['target_path'])){
			return $records[0]['target_path'];
		}else{
			return false;
		}		
	}
}