# Amazon MWS API for PHP

This is a lightweight library to interrogate the Amazon Marketplace Web Services

There is currently three API implementations:

* Feeds
* Orders
* Products

## Feeds

This API is used to submit Product information to Amazon Marketplace via the MarketplaceWebService

API reference guide can be found at [http://docs.developer.amazonservices.com/en_UK/feeds/index.html](http://docs.developer.amazonservices.com/en_UK/feeds/index.html)

## Orders

This API is used to retrieve Order information from Amazon Mawketplace via the MarketplaceWebService

API reference guide can be found at [http://docs.developer.amazonservices.com/en_UK/orders/2013-09-01/index.html](http://docs.developer.amazonservices.com/en_UK/orders/2013-09-01/index.html)

## Products

This API is used to query Amazon for Product information via the MarketplaceWebService

API reference guide can be found at [http://docs.developer.amazonservices.com/en_UK/products/index.html](http://docs.developer.amazonservices.com/en_UK/products/index.html)

# Examples

```php
<?php

$arrConfig = array(
   'SellerId' => 'MY_SELLER_ID',
   'MWSAuthToken' => 'MY_MWS_AUTH_TOKEN',
   'AWSAccessKeyId' => 'MY_AWS_ACCESS_KEY_ID',
   'SecretKey' => 'MY_SECRET_KEY',
   'Endpoint' => 'mws.amazonservices.co.uk'
);

$objAmazon = new \DawgUK\AmazonMWS\Amazon($arrConfig);

$arrListOrdersPayload = array(
   'MarketplaceId.Id.1' => 'A1F83G8C2ARO7P',
   'CreatedAfter' => '2016-01-01T19:00:00Z'
);

$objResponseXML = $objAmazon->call('Feeds/getFeedSubmissionList', $arrPayload);
print_r($objXml);
$arr_headers = $objAmazon->getHeaders();
print_r($arr_headers);

```