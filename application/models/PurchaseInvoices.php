<?php

/**
 * PurchaseInvoices
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ShineISP
 * 
 * @author     Shine Software <info@shineisp.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class PurchaseInvoices extends BasePurchaseInvoices
{
	/**
	 * grid
	 * create the configuration of the grid
	 */	
	public static function grid($rowNum = 10) {
		
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		
		$config ['datagrid'] ['columns'] [] = array ('label' => null, 'field' => 'i.purchase_id', 'alias' => 'purchase_id', 'type' => 'selectall' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'ID' ), 'field' => 'i.purchase_id', 'alias' => 'purchase_id', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Invoice Number' ), 'field' => 'i.number', 'alias' => 'number', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Supplier' ), 'field' => 'i.company', 'alias' => 'supplier', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Date' ), 'field' => 'i.creationdate', 'alias' => 'creationdate', 'sortable' => true, 'searchable' => true, 'type' => 'date' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Attachment' ), 'field' => 'i.document', 'alias' => 'attachment', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Payment Method' ), 'field' => 'i.method_id', 'alias' => 'method', 'sortable' => true, 'searchable' => true, 'type' => 'index', 'filterdata' => PaymentsMethods::getList() );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Total' ), 'field' => 'i.total_net', 'alias' => 'net', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'VAT' ), 'field' => 'i.total_vat', 'alias' => 'vat', 'sortable' => true, 'searchable' => true, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Grand Total' ), 'field' => 'i.total', 'alias' => 'total', 'sortable' => true, 'searchable' => true, 'type' => 'string', 'class' => 'highlight' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Category' ), 'field' => 'i.category_id', 'alias' => 'category', 'sortable' => true, 'type' => 'index', 'searchable' => true, 'filterdata' => PurchaseCategories::getList()  );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Statuses' ), 'field' => 's.status', 'alias' => 'status', 'sortable' => true, 'searchable' => true);
		
		$config ['datagrid'] ['fields'] =  "purchase_id, 
											DATE_FORMAT(creationdate, '".Settings::getMySQLDateFormat('dateformat')."') as creationdate, 
											DATE_FORMAT(expiringdate, '".Settings::getMySQLDateFormat('dateformat')."') as expiringdate, 
											number as number, 
											company as supplier, 
											document as attachment, 
											pm.method as method,
											pc.category as category,
											total_net as net, 
											total_vat as vat, 
											total as total,
											s.status as status";
		
		$config ['datagrid'] ['dqrecordset'] = Doctrine_Query::create ()
							                            ->select ( $config ['datagrid'] ['fields'] )
							                            ->from ( 'PurchaseInvoices i' )
							                            ->leftJoin( 'i.PaymentsMethods pm' )
							                            ->leftJoin( 'i.PurchaseCategories pc' )
							                            ->leftJoin( 'i.Statuses s' )
							                            ->orderBy ( 'creationdate desc' );		
		
		$config ['datagrid'] ['rownum'] = $rowNum;
		
		$config ['datagrid'] ['basepath'] = "/admin/purchases/";
		$config ['datagrid'] ['index'] = "purchase_id";
		$config ['datagrid'] ['rowlist'] = array ('10', '50', '100', '1000' );
		
		$config ['datagrid'] ['buttons'] ['edit'] ['label'] = $translator->translate ( 'Edit' );
		$config ['datagrid'] ['buttons'] ['edit'] ['cssicon'] = "edit";
		$config ['datagrid'] ['buttons'] ['edit'] ['action'] = "/admin/purchases/edit/id/%d";
		
		$config ['datagrid'] ['buttons'] ['delete'] ['label'] = $translator->translate ( 'Delete' );
		$config ['datagrid'] ['buttons'] ['delete'] ['cssicon'] = "delete";
		$config ['datagrid'] ['buttons'] ['delete'] ['action'] = "/admin/purchases/delete/id/%d";
		$config ['datagrid'] ['massactions'] = array ('bulk_pdf_export'=>'Pdf List', 'bulk_csv_export' => 'Csv Export');
		return $config;
	}	

	
    /**
     * Get a record by ID
     * @param $id
     * @return Doctrine Record
     */
    public static function find($id) {
        return Doctrine::getTable ( 'PurchaseInvoices' )->findOneBy ( 'purchase_id', $id );
    }
    
    /**
     * Get a record by invoice number
     * @param $number
     * @return Doctrine Record
     */
    public static function find_by_number($number) {
        return Doctrine::getTable ( 'PurchaseInvoices' )
        ->findOneBy ( 'number', $number );
    }
    
	/**
	 * Get a invoices by id 
	 * @param array $ids [1,2,3,4,...,n]
	 * @param string $fields
	 * @return Array
	 */
	public static function get_invoices($ids, $fields="*", $orderby=null) {
		return Doctrine_Query::create ()->select($fields)
										->from ( 'PurchaseInvoices i' )
										->leftJoin( 'i.PaymentsMethods pm' )
							            ->leftJoin( 'i.Statuses s' )
										->whereIn( "i.purchase_id", $ids)
										->orderBy(!empty($orderby) ? $orderby : "")
										->execute ( array (), Doctrine::HYDRATE_ARRAY );
	}    
    
    /**
     * getAll
     * Get all the records
     * @return Doctrine Record
     */
    public static function getAll() {
        return Doctrine::getTable ( 'PurchaseInvoices' )->findAll(Doctrine_Core::HYDRATE_ARRAY);
    }
    
    
    /**
     * Delete an order using its ID
     * @param $id
     * @return boolean
     */
    public static function DeleteByID($id) {
    	if(is_numeric($id)){
	    	self::DeleteAttachment($id);
	        return Doctrine_Query::create ()->delete ()->from ( 'PurchaseInvoices i' )->where ( 'purchase_id = ?', $id )->execute ();
    	}
        return false;
    }
    
    
    /*
     * getList
     * Get all the invoices for a select object
     */
    public static function getList($empty=false) {
        $items = array ();
        $arrTypes = Doctrine::getTable ( 'PurchaseInvoices' )->findAll ();
        if($empty){
            $items[] = "";
        }
        foreach ( $arrTypes->getData () as $c ) {
            $items [$c ['purchase_id']] = $z = sprintf("%03d", $c ['number']) . " - " . Shineisp_Commons_Utilities::formatDateOut($c ['creationdate']);
        }
        return $items;
    }

    /**
     * Save all the data
     * @param array $data
     */
    public static function saveAll($id, $data){
    	
    	// Set the new values
		if (is_numeric ( $id )) {
			$invoice = self::find($id);
		}else{
			$invoice = new PurchaseInvoices();
		}
		
		$invoice['creationdate'] = Shineisp_Commons_Utilities::formatDateIn($data['creationdate']);
		$invoice['expiringdate'] = Shineisp_Commons_Utilities::formatDateIn($data['expiringdate']);
		$invoice['paymentdate'] = !empty($data['paymentdate']) ? Shineisp_Commons_Utilities::formatDateIn($data['paymentdate']) : "";
		$invoice['number'] = $data['number'];
		$invoice['company'] = $data['company'];
		$invoice['category_id'] = $data['category_id'];
		$invoice['method_id'] = $data['method_id'];
		$invoice['total_net'] = $data['total_net'];
		$invoice['total_vat'] = $data['total_vat'];
		$invoice['total'] = $data['total'];
		$invoice['note'] = $data['note'];
		$invoice['status_id'] = $data['status_id'];
		
    	$invoice->save();
    	
    	self::UploadDocument($invoice['purchase_id']);
    	
    	return $invoice['purchase_id'];
    }
    
    /**
     * UploadDocument
     * the extensions allowed are JPG, GIF, PNG
     */
    public static function DeleteAttachment($id){
    	$purchase_invoice = self::find($id);
    	if($purchase_invoice){
    		$rs = $purchase_invoice->toArray();
    		if(file_exists(PUBLIC_PATH . "/documents/purchase_invoices/" . $rs['document'])){
    			@unlink(PUBLIC_PATH . "/documents/purchase_invoices/" . $rs['document']);
    			$purchase_invoice['document'] = NULL;
				$purchase_invoice->save ();
				return true;
    		}
    	} 
    	return false;
    }
    
    /**
     * UploadDocument
     * the extensions allowed are JPG, GIF, PNG
     */
    public static function UploadDocument($id){
    	try{
    		
	    	$attachment = new Zend_File_Transfer_Adapter_Http();
	    	
			$files = $attachment->getFileInfo();
			
			// Create the directory
			@mkdir ( PUBLIC_PATH . "/documents/purchase_invoices", 0777, true );
			
			// Set the destination directory
			$attachment->setDestination ( PUBLIC_PATH . "/documents/purchase_invoices/" );
			
			if ($attachment->receive()) {
				$purchase_invoice = self::find($id); 
				$purchase_invoice['document'] = $files['document']['name'];
				$purchase_invoice->save ();
				return true;
			}	
			
    	}catch (Exception $e){
			return false;	    		
    	}
    }	        
	
	/**
	 * get the purchase invoice summary for the selected year
	 * 
	 * @param integer $year
	 */
	public static function getSummary($year, $payments=true, $groupbyyear=false, $groupbyquarter=false, $groupbymonth=false) {
		if(!is_numeric($year)){
			return array();
		}
		
		$dq = Doctrine_Query::create ()
                            ->select ( "SUM(ip.total_net) as total,
                            			SUM(ip.total_vat) as vat,
                            			SUM(ip.total) as grandtotal" )
                            ->from ( 'PurchaseInvoices ip' )
                            ->where('YEAR(ip.creationdate) = ?', $year)
                            ->andWhere('ip.status_id = ?', Statuses::id("complete", "orders"));
                           
		if($groupbymonth){
			$dq->addGroupBy('MONTH(ip.creationdate)');
			$dq->addSelect("YEAR(ip.creationdate) as year");
			$dq->addSelect("MONTH(ip.creationdate) as month");
			$dq->addSelect("date_format(ip.creationdate,'%M') as monthname");
		}
		
		if($groupbyyear){
			$dq->addSelect("YEAR(ip.creationdate) as year");
			$dq->addGroupBy('YEAR(ip.creationdate)');
		}
		
		if($groupbyquarter){
			$dq->addSelect('YEAR(ip.creationdate) as year');
			$dq->addSelect('QUARTER(ip.creationdate) as quarter');
			$dq->groupBy("quarter, year")->orderBy('year, quarter');
		}
		
		$records = $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
		
// 		if($groupbymonth)
// 			Zend_Debug::dump($records);
        
        if($groupbymonth && $payments){                    
			for ($i=0;$i<count($records);$i++) {
	        	$records[$i]['subrecords'] = self::getAllPaymentsbyMonthYear($records[$i]['month'], $year);
	        }
        }           

        return $records;
		
	}    
	
	/**
	 * get the purchase invoice summary date ranges
	 * 
	 * @param integer $year
	 */
	public static function getWeekSummarybyDateRange($from, $to) {
	
		if(empty($from) || empty($to)){
			return array();
		}
		
		$records = Doctrine_Query::create ()
                            ->select ( "MONTH(ip.creationdate) as month,
                            			date_format(ip.creationdate,'%M') as monthname, 
                            			WEEK(ip.creationdate) as week,
                            			YEAR(ip.creationdate) as yearnum, 
                            			SUM(ip.total_net) as total,
                            			SUM(ip.total_vat) as vat,
                            			SUM(ip.total) as grandtotal" )
                            ->from ( 'PurchaseInvoices ip' )
                            ->where('ip.creationdate BETWEEN ? AND ?', array($from, $to))
                            ->andWhere('ip.status_id = ?', Statuses::id("complete", "orders"))
                            ->groupBy('YEARWEEK(ip.creationdate)')
                            ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
        return $records;
		
	}    
    
	
	public static function getAllPaymentsbyMonthYear($month, $year) {
		
		if(!is_numeric($month) || !is_numeric($year)){
			return array();
		}
		
		
		$records = Doctrine_Query::create ()
                            ->select ( "ip.purchase_id,
                            			pm.method as method,
                            			ip.number as invoice,	
                            			DATE_FORMAT(ip.creationdate, '".Settings::getMySQLDateFormat('dateformat')."') as date, 		
                            			DATE_FORMAT(ip.expiringdate, '".Settings::getMySQLDateFormat('dateformat')."') as expiring_date, 		
                            			ip.company as company,
                            			ip.total_net as total,
                            			ip.total_vat as vat,
                            			ip.total as grandtotal" )
                            ->from ( 'PurchaseInvoices ip' )
                            ->leftJoin('ip.PaymentsMethods pm')
                            ->where('YEAR(ip.creationdate) = ?', $year)
                            ->andWhere('MONTH(ip.creationdate) = ?', $month)
                            ->andWhere('ip.status_id = ?', Statuses::id("complete", "orders"))
                            ->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
         return $records;
	}
	
	/**
	 * statsgrid
	 * create a new datagrid
	 */	
	public static function getSummaryGrid($helper, $year) {
		
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Month Number' ), 'field' => 'MONTH(ip.creationdate)', 'alias' => 'month', 'sortable' => false, 'searchable' => false, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Month' ), 'field' => 'date_format(ip.creationdate, "%M")', 'alias' => 'monthname', 'sortable' => false, 'searchable' => false, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Year' ), 'field' => 'YEAR(ip.creationdate)', 'alias' => 'yearnum', 'sortable' => false, 'searchable' => false, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Total' ), 'field' => 'SUM(ip.total_net)', 'alias' => 'total', 'sortable' => false, 'searchable' => false, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'VAT' ), 'field' => 'SUM(ip.total_vat)', 'alias' => 'vat', 'sortable' => false, 'searchable' => false, 'type' => 'string' );
		$config ['datagrid'] ['columns'] [] = array ('label' => $translator->translate ( 'Grand Total' ), 'field' => 'SUM(ip.total)', 'alias' => 'grandtotal', 'sortable' => false, 'searchable' => false, 'type' => 'string' );
		$config ['datagrid'] ['hassubrecords'] = true;
		
		$config ['datagrid'] ['fields'] =  "ip.purchase_id,
	                            			pm.method_id as method_id,
	                            			pm.method as method,
	                            			ip.number as invoice,			
	                            			ip.paymentdate as date,
	                            			ip.total_net as outcome,
	                            			ip.total_vat as vat,
	                            			ip.total as grandtotal";
		
		$config ['datagrid'] ['recordset'] = self::getSummary($year,true, false, false, true);
		$config ['datagrid'] ['placeholder'] = "expenses";
		$config ['datagrid'] ['id'] = "expenses";
		$config ['datagrid'] ['rownum'] = 12;
		$config ['datagrid'] ['title'] = $translator->translate('Expenses data report');
		
		$config ['datagrid'] ['basepath'] = "/admin/reports/show/type/profitofyear/";
		
		$helper->datagrid->setModule ( "purchaseinvoices" )->setModel ( new PurchaseInvoices );
		$helper->datagrid->setConfig ( $config )->datagrid ();
	}	
		
    
	######################################### BULK ACTIONS ############################################
		
	
	/**
	 * export the content in a pdf file
	 * @param array $items
	 */
	public function bulk_pdf_export($items) {
		$isp = Isp::getActiveISP();
		$pdf = new Shineisp_Commons_PdfList();
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		
		// Get the records from the purchase invoices table
		$invoices = self::get_invoices($items, "purchase_id, 
												DATE_FORMAT(creationdate, '".Settings::getMySQLDateFormat('dateformat')."') as creationdate, 
												number as number, 
												company as company, 
												pm.method as method,
												total_net as net, 
												total_vat as vat, 
												total as total,
												s.status as status", 'number, creationdate');

		// Create the PDF header
		$grid['headers']['title'] = $translator->translate('Purchases Invoices List');
		$grid['headers']['subtitle'] = $translator->translate('List of the the purchases invoices');
		$grid['footer']['text'] = $isp['company'] . " - " . $isp['website'];
		 
		if(!empty($invoices[0]))

			$total = 0;
			$vat = 0;
			$grandtotal = 0;
			
			// Create the columns of the grid
			$grid ['columns'] [] = array ("value" => $translator->translate('Date'), 'size' => 100);
			$grid ['columns'] [] = array ("value" => $translator->translate('Number'), 'size' => 50);
			$grid ['columns'] [] = array ("value" => $translator->translate('Company'));
			$grid ['columns'] [] = array ("value" => $translator->translate('Method'), 'size' => 50);
			$grid ['columns'] [] = array ("value" => $translator->translate('Total Net'), 'size' => 50);
			$grid ['columns'] [] = array ("value" => $translator->translate('VAT'), 'size' => 50);
			$grid ['columns'] [] = array ("value" => $translator->translate('Total'), 'size' => 50);
			$grid ['columns'] [] = array ("value" => $translator->translate('Status'), 'size' => 50);
			
			// Getting the records values and delete the first column the customer_id field.
			foreach ($invoices as $item){
				$values = array_values($item);
				array_shift($values);
				$grid ['records'] [] = $values;
				
				$total += is_numeric($values[4]) ? $values[4] : 0; 
				$vat += is_numeric($values[5]) ? $values[5] : 0; 
				$grandtotal += is_numeric($values[6]) ? $values[6] : 0; 
			}
				
			$grid ['records'] [] = array('','','','', $total, $vat, $grandtotal, '');
			
			// Create the PDF
			die($pdf->create($grid));
		
		return false;	
	}			
	
	/**
	 * export the content in a csv file
	 * @param array $items
	 */
	public function bulk_csv_export($items) {
		$translator = Shineisp_Registry::getInstance ()->Zend_Translate;
		
		// Get the records from the order table
		$orders = self::get_invoices($items, "purchase_id, 
												DATE_FORMAT(creationdate, '".Settings::getMySQLDateFormat('dateformat')."') as date, 
												number as number, 
												company as company, 
												pm.method as payment,
												total_net as net, 
												total_vat as vat, 
												total as total,
												s.status as status", 'number, creationdate');

		if(!empty($orders[0])){

			$tmpname = Shineisp_Commons_Utilities::GenerateRandomString();
			@mkdir ( PUBLIC_PATH . "/tmp/");

			// Create the file and open it
			$fp = fopen(PUBLIC_PATH . "/tmp/" . $tmpname . '.csv', 'w+');

			// Add the headers
			$headers = array_keys($orders[0]);
			if(!empty($headers)){
				array_shift($headers);
				foreach ($headers as $item) {
					$newHeaders[] = $translator->translate(ucfirst($item));
				}
				fputcsv($fp, $newHeaders);
			}
			
			// For each record in the recordset
			foreach ($orders as $item){
				array_shift($item);
				fputcsv($fp, $item);
			}

			// Close the file
			fclose($fp);
			
			// Return the link
			die(json_encode(array('url' => "/tmp/" . $tmpname . ".csv")));
		}
		return false;	
	}			
}