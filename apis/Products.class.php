<?php
/**
 * Products.class.php
 * 
 * @author David Wilcock <dwilcock@docnet.nu>
 * @copyright Doctor Net Ltd &copy; 2014
 * @package
 */

class Products extends AmazonMWS {#

   protected $strApiVersion = '2011-10-01';

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
    * @return SimpleXMLElement
    */
   public function getMatchingProduct($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'GetMatchingProduct';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   public function listMatchingProducts($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'ListMatchingProducts';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

}