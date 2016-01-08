<?php

/**
 * AmazonMWS.class.php
 *
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @copyright David Wilcock (roflcopter.cc) 2014, 2016
 */

namespace DawgUK;

abstract class AmazonMWS {

   /**
    * The Client configuration
    *
    * @var array
    */
   public $arrConfig = array();

   /**
    * The message payload
    *
    * @var array
    */
   protected $arrMessagePayload = array();

   /**
    * The object type
    *
    * @var string
    */
   protected $strObjectType = '';

   /**
    * An error of API errors
    *
    * @var array
    */
   protected $arrApiErrors = array();

   /**
    * The XML payload
    *
    * @var null
    */
   protected $strXMLPayload = null;

   /**
    * The headers from the last response
    *
    * @var array
    */
   protected $arrHeaders = array();

   /**
    * Takes an array of client configuration values.
    *
    * @param array $arrConfig
    */
   public function __construct(array $arrConfig) {
      $this->arrConfig = $arrConfig;
      $this->arrMessagePayload = array(
         'AWSAccessKeyId' => $this->arrConfig['AWSAccessKeyId'],
         'SellerId' => $this->arrConfig['SellerId'],
         'MWSAuthToken' => $this->arrConfig['MWSAuthToken'],
         'SignatureVersion' => '2',
         'SignatureMethod' => 'HmacSHA256'
      );
      $this->strObjectType = $this->getApiName();
   }

   /**
    * Gets the service status. Standard across all APIs
    *
    * @return \SimpleXMLElement
    */
   public function getServiceStatus() {
      $this->arrMessagePayload['Action'] = 'GetServiceStatus';
      try {
         $this->preparePayload();
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (\Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   /**
    * Prepares the payload, ready for sending
    *
    * @param array $arrPayloadConfig
    * @throws \Exception
    */
   protected function preparePayload($arrPayloadConfig = array()) {
      if (count($arrPayloadConfig)) {
         if (isset($arrPayloadConfig['XML'])) {
            $this->strXMLPayload = $arrPayloadConfig['XML'];
            unset($arrPayloadConfig['XML']);
         }
         foreach ($arrPayloadConfig as $strKey => $strValue) {
            $this->arrMessagePayload[$strKey] = $strValue;
         }
      }
      if (!isset($this->arrMessagePayload['Action'])) {
         throw new \Exception("Action parameter was not set when preparing payload");
      }
      $this->arrMessagePayload['Timestamp'] = gmdate("Y-m-d\TH:i:s\Z");
      $this->arrMessagePayload['Version'] = $this->getApiVersion();
      uksort($this->arrMessagePayload, 'strnatcmp');
   }

   /**
    * Builds the query string using the RFC encoding type required by Amazon
    *
    * @param $arrData
    * @return string
    */
   protected function buildQuery($arrData) {
      return http_build_query($arrData, null, "&", PHP_QUERY_RFC3986);
   }

   /**
    * Signs the query using some magic!
    *
    * @param $strQuery
    * @return mixed
    */
   protected function signQuery($strQuery) {
      $strToSign = "POST" . "\n" . $this->arrConfig['Endpoint'] . "\n/";
      if ($this->usesApiVersionInURL()) {
         $strToSign .= $this->strObjectType . "/" . $this->arrMessagePayload['Version'];
      }
      $strToSign .=  "\n" . $strQuery;
      return str_replace("%7E", "~", rawurlencode(base64_encode(hash_hmac("sha256", $strToSign, $this->arrConfig['SecretKey'], TRUE))));
   }

   /**
    * Jump through some hoops to generate the correct API endpoint to query.
    *
    * @return string
    */
   protected function getPostUrl() {
      $strQuery = $this->buildQuery($this->arrMessagePayload);
      $strSignature = $this->signQuery($strQuery);
      $strHost = $this->arrConfig['Endpoint'] . "/";
      if ($this->usesApiVersionInURL()) {
         $strHost .= $this->strObjectType . "/" . $this->arrMessagePayload['Version'];
      }
      return "https://" . $strHost . "?" . $strQuery . "&Signature=" . $strSignature;
   }

   /**
    * Does the actual CURL request, with a sprinkle of extra magic to work around some bonkers API design decisions
    * made by Amazon
    *
    * @return mixed
    * @throws \Exception
    */
   protected function makeApiCall() {

      // reset these whenever we make a new query
      $this->arrApiErrors = array();

      $objCurl = curl_init();
      $arrCurlOptions = array(
         CURLOPT_URL => $this->getPostUrl(),
         CURLOPT_POST => TRUE,
         CURLOPT_POSTFIELDS => array('Content-Type: text/xml', 'User-Agent: ' . $this->arrConfig['UserAgent']),
         CURLOPT_HEADER => TRUE,
         CURLOPT_VERBOSE => FALSE, // !IMPORTANT! TRUE for debug only - setting this to true will BREAK EVERYTHING
         CURLOPT_RETURNTRANSFER => TRUE,
         CURLOPT_SSL_VERIFYHOST => FALSE,
         CURLOPT_SSL_VERIFYPEER => FALSE
      );

      // If there is some XML payload detected, we switch to using that in the body instead, and add the hash in the headers
      if (NULL !== $this->strXMLPayload) {
         $arrCurlOptions[CURLOPT_POSTFIELDS] = $this->strXMLPayload;
         $arrCurlOptions[CURLOPT_HTTPHEADER] = array("Content-MD5: " . base64_encode(md5($this->strXMLPayload, TRUE)));
      }

      curl_setopt_array($objCurl, $arrCurlOptions);
      $strResponse = curl_exec($objCurl);
      $arrInfo = curl_getinfo($objCurl);
      $intHeaderSize = $arrInfo['header_size'];
      $strBody = trim(substr($strResponse, $intHeaderSize));
      $strHeaders = substr($strResponse, 0, $intHeaderSize);

      if ($arrInfo['http_code'] !== 200) {
         $this->arrApiErrors['Response'] = $strResponse;
         $this->arrApiErrors['CurlInfo'] = $arrInfo;
         throw new \Exception("Curl exception: HTTP response was not a 200");
      }

      $this->arrHeaders = $this->http_parse_headers($strHeaders);

      return $strBody;
   }

   /**
    * Turns the headers string into a nice array, keyed on the header name.
    *
    * @param $raw_headers
    * @return array
    */
   protected function http_parse_headers ($raw_headers) {
      $headers = [];

      foreach (explode("\n", $raw_headers) as $i => $h) {
         $h = explode(':', $h, 2);

         if (isset($h[1])) {
            $headers[$h[0]] = trim($h[1]);
         }
      }

      return $headers;
   }

   /**
    * Returns the headers from the last response
    *
    * @return array
    */
   public function getHeaders() {
      return $this->arrHeaders;
   }

   /**
    * Returns the array of API errors
    *
    * @return array
    */
   public function getLastApiErrors() {
      return $this->arrApiErrors;
   }

   /**
    * Tries to parse the response as an XML document.
    *
    * @param $strResponse
    * @return \SimpleXMLElement
    * @throws \Exception
    */
   protected function attemptXMLParse($strResponse) {
      libxml_use_internal_errors(TRUE);
      $objXML = simplexml_load_string($strResponse);
      $arrErrors = libxml_get_errors();
      if (count($arrErrors)) {
         foreach ($arrErrors as $objError) {
            $this->arrApiErrors['XML'][] = $objError->message;
         }
         libxml_clear_errors();
         throw new \Exception("An XML error occurred");
      }
      libxml_use_internal_errors(FALSE);
      return $objXML;
   }

   /**
    * Used for debug only
    *
    * @param \Exception $objException
    */
   protected function outputExceptionData(\Exception $objException) {
      echo "An exception was caught: " . $objException->getMessage() . "\n";
      if (count($this->getLastApiErrors())) {
         print_r($this->getLastApiErrors());
      }
   }

   /**
    * Process a payload
    *
    * Make API call, parse response XML, catch & output failure
    *
    * @param $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   protected function processPayload($arrPayloadConfig) {
      try {
         $this->preparePayload($arrPayloadConfig);
         $strResponse = $this->makeApiCall();
         return $this->attemptXMLParse($strResponse);
      } catch (\Exception $objException) {
         $this->outputExceptionData($objException);
      }
   }

   /**
    * Different APIs, different version strings
    *
    * @return string
    */
   abstract protected function getApiVersion();

   /**
    * Does this API prepend version information on the URL?
    *
    * @return boolean
    */
   abstract protected function usesApiVersionInURL();

   /**
    * Gets the name of the API, e.g. "Orders" or "Feeds"
    *
    * @return mixed
    */
   abstract protected function getApiName();

}