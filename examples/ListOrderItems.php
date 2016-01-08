<?php
/**
 * ListOrderItems.php
 *
 * @author David Wilcock (dave.wilcock@gmail.com)
 * @copyright David Wilcock 2016
 */

require_once '../vendor/autoload.php';
require_once 'config.php';

$objAmazon = new \DawgUK\AmazonMWS\Amazon($arrConfig);

$arrListOrderItemsPayload = array(
   'AmazonOrderId' => '202-0662805-5649161'
);

try {
   $objResponseXML = $objAmazon->call('Orders/listOrderItems', $arrListOrderItemsPayload);
   print_r($objResponseXML);
} catch (\Exception $objException) {
   echo "Exception " . $objException->getMessage() . "\n";
}
