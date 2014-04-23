<?php

/**
 * test.php
 *
 * Used to perform testing
 */
require_once 'Amazon.class.php';
require_once 'config.php';

$arrConfig = array(
   'SellerId' => SELLER_ID,
   'AWSAccessKeyId' => AWS_ACCESS_KEY,
   'SecretKey' => SECRET_KEY,
   'Endpoint' => 'mws.amazonservices.co.uk'
);

$arrListOrdersPayload = array(
   'MarketplaceId.Id.1' => 'A1F83G8C2ARO7P',
   'CreatedAfter' => '2014-01-01T19:00:00Z'
);

$arrGetOrderPayload = array(
   'AmazonOrderId.Id.1' => '202-0662805-5649161'
);

$arrListOrderItemsPayload = array(
   'AmazonOrderId' => '202-0662805-5649161'
);

$arrListOrdersByNextTokenPayload = array(
   'NextToken' => 'ql52N7+XpIGaJqJYLDm0ZIfVkJJPpovRM3fK0yH9tUeYPwbTF1dYxrQzazHyYVyLqBXdLk4iogyhLzLeiQQz7/o7Z5d5lHG33qJ4TrcuB0yUrelVme04kSJ0wMvlylZkWQWPqGlbsnOf1o9BEOqRQXnVBbHUHDQ6/VVle9FwhVJYP0+64m2KUruZIF9n45mtnrZ4AbBdBTdJIrj5VxaB/CGvlnb+ukgrjjp4oQ6d4YW5HmwX5IAmwKfxnqm3JqvZRSesN4tSbFZdMEcXMarTFVR6+v7+2qlBWjeevn2b0x2Z8nDHf10QkGIyYonIXF6oHNiFeFf3z1VFZqNvoAFJg2780dMT58APUOerpdDLDYbLUs26eqEcqvmxWiV6K24ErToAsYN83sHgkdZPXhcRcOBdlk8+GYKHJRIlIooizto='
);

$arrSubmitFeedPayload = array(
   'FeedType' => '_POST_PRODUCT_DATA_',
   'XML' => '<?xml version="1.0" encoding="utf-8"?>'
);

$arrGetFeedSubmissionResultPayload = array(
   'FeedSubmissionId' => '8504635476'
);

$arrGetMatchingProductPayload = array(
   'MarketplaceId' => 'A1F83G8C2ARO7P',
   'ASINList.ASIN.1' => 'B00CTVTJ4Q'
);

$arrListMatchingProducts = array(
   'MarketplaceId' => 'A1F83G8C2ARO7P',
   'Query' => 'Tetley Tea'
);

$objAmazon = new Amazon($arrConfig);
//$objXml = $objAmazon->call('Orders/ListOrders', $arrListOrdersPayload);
//$objXml = $objAmazon->call('Orders/GetServiceStatus');
//$objXml = $objAmazon->call('Orders/GetOrder', $arrGetOrderPayload);
//$objXml = $objAmazon->call('Orders/ListOrderItems', $arrListOrderItemsPayload);
//$objXml = $objAmazon->call('Orders/ListOrdersByNextToken', $arrListOrdersByNextTokenPayload);
//$objXml = $objAmazon->call('Feeds/SubmitFeed', $arrSubmitFeedPayload);
//$objXml = $objAmazon->call('Feeds/GetFeedSubmissionResult', $arrGetFeedSubmissionResultPayload);
//$objXml = $objAmazon->call('Products/GetMatchingProduct', $arrGetMatchingProductPayload);

/**
 * Special cases for Products API - namespaced XML! Hurrah!
 */
$objXml = $objAmazon->call('Products/ListMatchingProducts', $arrListMatchingProducts);
$arrNs = $objXml->getNameSpaces(TRUE);
foreach ($objXml->ListMatchingProductsResult->Products->Product as $objProductNode) {
   echo (string) $objProductNode->Identifiers->MarketplaceASIN->ASIN . "\n";
   $objItemAttributes = $objProductNode->AttributeSets->children($arrNs["ns2"]);
   echo (string) $objItemAttributes->ItemAttributes->ProductGroup . "\n";
   echo (string) $objItemAttributes->ItemAttributes->Brand . "\n";
   echo (string) $objItemAttributes->ItemAttributes->Title . "\n";
   echo (string) $objItemAttributes->ItemAttributes->ListPrice->Amount . "\n";
   echo (string) $objItemAttributes->ItemAttributes->Publisher . "\n\n";

   //print_r($objItemAttributes);
}