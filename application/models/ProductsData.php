<?php

/**
 * ProductsData
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class ProductsData extends BaseProductsData {
	
    /**
     * findbyProductID
     * Get a record by ID
     * @param $id
     * @return Doctrine Record
     */
    public static function findbyProductID($id, $locale = 1) {
        
    	
    	$record = Doctrine_Core::getTable('ProductsData')
				    ->createQuery('u')
				    ->where ( "product_id = ?", $id )
                    ->addWhere ( "language_id = ?", $locale )
				    ->fetchOne();
	
        return $record;
    }
    	
	/*
	 * If one of the fields is empty get the default translation
	 */
	public static function checkTranslation($product) {
		$TranslateAgain = false;
		
		if (array_key_exists ( 'name', $product ) && empty ( $product ['name'] )) {
			$TranslateAgain = true;
		}
		
		if (array_key_exists ( 'shortdescription', $product ) && empty ( $product ['shortdescription'] )) {
			$TranslateAgain = true;
		}
		
		if (array_key_exists ( 'description', $product ) && empty ( $product ['description'] )) {
			$TranslateAgain = true;
		}
		
		if ($TranslateAgain) {
			$dq = Doctrine_Query::create ()->from ( 'ProductsData pd' )->where ( 'pd.language_id = ?', 1 )->addWhere ( 'pd.product_id = ?', $product ['product_id'] );
			$newproduct = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
			if (! empty ( $newproduct [0] )) {
				$product ['name'] = $newproduct [0] ['name'];
				$product ['shortdescription'] = $newproduct [0] ['shortdescription'];
				$product ['description'] = $newproduct [0] ['description'];
			}
		}
		
		return $product;
	}
}