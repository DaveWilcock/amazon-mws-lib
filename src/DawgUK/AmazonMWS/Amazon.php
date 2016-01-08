<?php

/**
 * Amazon.class.php
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @copyright David Wilcock (roflcopter.cc) 2014, 2016
 */

namespace DawgUK\AmazonMWS;

class Amazon {

   /**
    * The application name
    */
   const APPLICATION_NAME = 'AmazonAPILib';

   /**
    * The application version
    */
   const APPLICATION_VERSION = '0.5';

   /**
    * The array of client configuration information
    *
    * @var array
    */
   protected $arrConfig;

   /**
    * The headers from the last response
    *
    * @var array
    */
   protected $arrHeaders = array();

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
    * @throws \Exception
    */
   public function call($strCallType, $arrPayloadConfig = array()){

      $arrBits = explode("/", $strCallType);

      if (count($arrBits) != 2) {
         throw new \Exception("Unknown call type passed: " . $strCallType);
      }
      $strApiSection = __NAMESPACE__ . "\\apis\\" . $arrBits[0];
      $strOperation = $arrBits[1];

      if (class_exists($strApiSection)) {
         /** @var \DawgUK\AmazonMWS $objClass */
         $objClass = new $strApiSection($this->arrConfig);
      } else {
         throw new \Exception("Unknown API section: " . $strApiSection);
      }

      if (method_exists($objClass, $strOperation)) {
         $strResponse = $objClass->$strOperation($arrPayloadConfig);
         $this->arrHeaders = $objClass->getHeaders();

         return $strResponse;
      } else {
         throw new \Exception("Unknown API operation: " . $strOperation);
      }

   }

   /**
    * Get the headers from the last response
    *
    * @return array
    */
   public function getHeaders() {
      return $this->arrHeaders;
   }

}