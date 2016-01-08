<?php
/**
 * Products.class.php
 *
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @copyright David Wilcock (roflcopter.cc) 2014, 2016
 */

namespace DawgUK\AmazonMWS\apis;

use DawgUK\AmazonMWS;

class Products extends AmazonMWS {

   protected $strApiVersion = '2011-10-01';
   protected $strApiName = 'Products';

   /**
    * Gets the name of the API, e.g. "Orders" or "Feeds"
    *
    * @return mixed
    */
   protected function getApiName() {
      return $this->strApiName;
   }

   /**
    * Different APIs, different version strings
    *
    * @return string
    */
   protected function getApiVersion() {
      return $this->strApiVersion;
   }

   /**
    * Does this API prepend version information on the URL?
    *
    * @return boolean
    */
   protected function usesApiVersionInURL() {
      return TRUE;
   }

   /**
    * Returns matching products for Marketplace and ASIN[]
    *
    * @param $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function getMatchingProduct($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'GetMatchingProduct';
      return $this->processPayload($arrPayloadConfig);
   }

   /**
    * Lists matching products for a given query
    *
    * @param $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function listMatchingProducts($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'ListMatchingProducts';
      return $this->processPayload($arrPayloadConfig);
   }

}