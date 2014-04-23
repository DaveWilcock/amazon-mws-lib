<?php
/**
 * Feeds.class.php
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @copyright David Wilcock (blinkingduck.co.uk) 2014
 */

class Feeds extends AmazonMWS {

   /**
    * The API version
    *
    * @var string
    */
   protected $strApiVersion = '2009-01-01';

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
      return FALSE;
   }

   /**
    * Submits a feed
    *
    * @param $arrPayloadConfig
    * @return SimpleXMLElement
    */
   public function submitFeed($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'SubmitFeed';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   /**
    * Gets feed submission result
    *
    * @param $arrPayloadConfig
    * @return SimpleXMLElement
    */
   public function getFeedSubmissionResult($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'GetFeedSubmissionResult';
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }
}