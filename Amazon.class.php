<?php

require_once 'AmazonMWS.class.php';

/**
 * Amazon.class.php
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @copyright David Wilcock (blinkingduck.co.uk) 2014
 */

class Amazon {

   /**
    * The application name
    */
   const APPLICATION_NAME = 'AmazonSlim';

   /**
    * The application version
    */
   const APPLICATION_VERSION = '0.3';

   /**
    * The array of client configuration information
    *
    * @var array
    */
   protected $arrConfig;

   /**
    * Builds up the UserAgent string based on application information
    *
    * @param array $arrConfig
    */
   public function __construct(array $arrConfig){
      $this->arrConfig = $arrConfig;
      $this->arrConfig['UserAgent'] = self::APPLICATION_NAME . "/" . self::APPLICATION_VERSION . "(Language=PHP)";
   }

   /**
    * Directs the requested call
    *
    * @param string $strCallType
    * @param array $arrPayloadConfig
    * @throws Exception
    */
   public function call($strCallType, $arrPayloadConfig = array()){

      $arrBits = explode("/", $strCallType);

      if (count($arrBits) != 2) {
         throw new Exception("Unknown call type passed: " . $strCallType);
      }
      $strApiSection = $arrBits[0];
      $strOperation = $arrBits[1];

      if (class_exists($strApiSection)) {
         $objClass = new $strApiSection($this->arrConfig);
      } else {
         throw new Exception("Unknown API section: " . $strApiSection);
      }

      if (method_exists($objClass, $strOperation)) {
         return $objClass->$strOperation($arrPayloadConfig);
      } else {
         throw new Exception("Unknown API operation: " . $strOperation);
      }

   }

}