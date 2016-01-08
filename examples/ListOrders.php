<?php
/**
 * ListOrders.php
 *
 * @author David Wilcock (dave.wilcock@gmail.com)
 * @copyright David Wilcock 2016
 */

require_once '../vendor/autoload.php';
require_once 'config.php';

$objAmazon = new \DawgUK\AmazonMWS\Amazon($arrConfig);

$arrListOrdersPayload = array(
   'MarketplaceId.Id.1' => 'A1F83G8C2ARO7P',
   'CreatedAfter' => '2016-01-01T19:00:00Z'
);

try {
   $objResponseXML = $objAmazon->call('Orders/listOrders', $arrListOrdersPayload);
   print_r($objResponseXML);
} catch (\Exception $objException) {
   echo "Exception " . $objException->getMessage() . "\n";
}
