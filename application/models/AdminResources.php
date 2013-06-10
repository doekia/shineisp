<?php

/**
 * AdminResources
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class AdminResources extends BaseAdminResources
{
	/**
	 * Get all the resources
	 */
	public static function getResources(){
		return Doctrine_Query::create ()->from ( 'AdminResources r' )->execute(array(), Doctrine::HYDRATE_ARRAY);
	}
	
	/**
	 * Get all default module resources within an array list
	 */
	public static function getDefaultModuleList(){
		$data = array();
		$resources = Doctrine_Query::create ()->from ( 'AdminResources r' )
												->where('r.module = ?', 'default')
											  ->execute(array(), Doctrine::HYDRATE_ARRAY);
		foreach ($resources as $resource){
			$data[$resource['resource_id']] = $resource['module'] . ":" . $resource['controller'] . " - " . $resource['name'];
		}
		
		return $data;
	}
	
	/**
	 * Get all the resources within an array list
	 */
	public static function getList(){
		$data = array();
		$resources = Doctrine_Query::create ()->from ( 'AdminResources r' )
											  ->execute(array(), Doctrine::HYDRATE_ARRAY);
		foreach ($resources as $resource){
			$data[$resource['resource_id']] = $resource['module'] . ":" . $resource['controller'] . " - " . $resource['name'];
		}
		
		return $data;
	}
	
	/**
	 * Get the resources by the parent id
	 * @param integer $id
	 */
	public static function getbyParentId($id) {
		$auth = Zend_Auth::getInstance ();
		$records = Doctrine_Query::create ()->from ( 'AdminResources r' )->where('r.parent_id = ? ', $id)->andWhere('r.admin = ? ', 1);
		
		// Hide some resources within the list
		if ($auth->hasIdentity()) {
			$identity = $auth->getIdentity();
			if($identity['AdminRoles']['name'] != "administrator"){
				$records->AndWhere('r.hidden = ? ', 0);
			}
		}
		
		return $records->execute(array(), Doctrine::HYDRATE_ARRAY);
	}
	
	/**
	 * This method creates the resource tree
	 * @param integer $id [0 for the root]
	 * @param array $selecteditem 
	*/
	public static function createTree($id, $selecteditem = array()) {
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		
		$res = array ();
		$isfolder = false;
		$items = self::getbyParentId ( $id, 0 );
		foreach ( $items as $resource ) {
			$subres = self::createTree ( $resource ['resource_id'], $selecteditem );
			$isfolder = ($subres) ? true : false;
			$selected = in_array ( $resource ['resource_id'], $selecteditem ) ? true : false;
			if ($subres) {
				$expanded = in_array ( $resource ['resource_id'], $selecteditem ) ? true : false;
				$res [] = array ('key' => $resource ['resource_id'], 'title' => $translator->translate($resource ['name']), 'expand' => $expanded, 'select' => $selected, 'isFolder' => $isfolder, 'children' => $subres, 'hideCheckbox' => true );
			} else {
				$res [] = array ('key' => $resource ['resource_id'], 'title' => $translator->translate($resource ['name']), 'select' => $selected );
			}
		}
		return $res;
	}
}