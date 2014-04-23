<?php
/**
 * Orders.class.php
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @copyright David Wilcock (blinkingduck.co.uk) 2014
 */

class Orders extends AmazonMWS {

   /**
    * The API version
    *
    * @var string
    */
   protected $strApiVersion = '2013-09-01';

   /**
    * Returns the API version
    *
    * @return string
    */
   protected function getApiVersion() {
      return $this->strApiVersion;
   }

   /**
    * Does this API prepend version information in the URL?
    *
    * @return bool
    */
   protected function usesApiVersionInURL() {
      return TRUE;
   }

   /**
    * Returns a list of all Orders based on filtering parameters.
    *
    * @param $arrPayloadConfig
    * @return SimpleXMLElement
    */
   public function listOrders($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'ListOrders';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   /**
    * Pagination version of listOrders
    *
    * @param $arrPayloadConfig
    * @return SimpleXMLElement
    */
   public function listOrdersByNextToken($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'ListOrdersByNextToken';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   /**
    * Returns detailed information about an Order
    *
    * @param $arrPayloadConfig
    * @return SimpleXMLElement
    */
   public function getOrder($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'GetOrder';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   /**
    * Returns detailed information about items on an Order
    *
    * @param $arrPayloadConfig
    * @return SimpleXMLElement
    */
   public function listOrderItems($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'ListOrderItems';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   /**
    * Pagination version of listOrderItems - rarely used!
    *
    * @param $arrPayloadConfig
    * @return SimpleXMLElement
    */
   public function listOrderItemsByNextToken($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'ListOrderItemsByNextToken';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

}