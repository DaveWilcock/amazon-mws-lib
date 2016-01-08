<?php
/**
 * Feeds.class.php
 * 
 * @author David Wilcock <dave.wilcock@gmail.com>
 * @copyright David Wilcock (roflcopter.cc) 2014, 2016
 */

namespace DawgUK\AmazonMWS\apis;

use DawgUK\AmazonMWS;

class Feeds extends AmazonMWS {

   protected $strApiVersion = '2009-01-01';
   protected $strApiName = 'Feeds';

   /**
    * Gets the name of the API, e.g. "Orders" or "Feeds"
    *
    * @return mixed
    */
   protected function getApiName() {
      return $this->strApiName;
   }

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
    * Required payload parameters are:
    *
    *    FeedType : The feed type string, e.g. _POST_PRODUCT_DATA_
    *    XML      : The
    *
    * @param $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function submitFeed($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'SubmitFeed';
      return $this->processPayload($arrPayloadConfig);
   }

   /**
    * Cancels feed submissions. Scarily, has no REQUIRED parameters in the API.
    *
    * Optional payload parameters:
    *
    *    FeedSubmissionIdList.Id.n  : The ID returned from a prior submitFeed action
    *    FeedTypeList.Type.n                 : The feed type string, e.g. _POST_PRODUCT_DATA_
    *    SubmittedFromDate                   : yyyy-mm-dd H:i:s
    *    SubmittedToDate                     : yyyy-mm-dd H:i:s
    *
    * @param null $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function cancelFeedSubmissions($arrPayloadConfig = null) {
      $this->arrMessagePayload['Action'] = 'CancelFeedSubmissions';
      return $this->processPayload($arrPayloadConfig);
   }

   /**
    * Gets feed submission result.
    *
    * Required payload parameters:
    *
    *    FeedSubmissionId : The ID returned from a prior submitFeed action
    *
    * @param $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function getFeedSubmissionResult($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'GetFeedSubmissionResult';
      return $this->processPayload($arrPayloadConfig);
   }

   /**
    * Returns a list of submitted feeds
    *
    * Optional payload parameters:
    *
    *    MaxCount                            : The maximum number of results to return
    *    FeedTypeList.Type.n                 : The feed type string, e.g. _POST_PRODUCT_DATA_
    *    FeedProcessingStatusList.Status.n   : The feed processing status string, e.g. _IN_PROGRESS_
    *    SubmittedFromDate                   : yyyy-mm-dd H:i:s
    *    SubmittedToDate                     : yyyy-mm-dd H:i:s
    *
    * @param array $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function getFeedSubmissionList($arrPayloadConfig = array()) {
      $this->arrMessagePayload['Action'] = 'GetFeedSubmissionList';
      return $this->processPayload($arrPayloadConfig);
   }

   /**
    * Returns a list of submitted feeds by token
    *
    * Required payload parameters are:
    *
    *    NextToken   : The token from a prior getFeedSubmissionList response
    *
    * @param $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function getFeedSubmissionListByNextToken($arrPayloadConfig) {
      $this->arrMessagePayload['Action'] = 'GetFeedSubmissionListByNextToken';
      return $this->processPayload($arrPayloadConfig);
   }

   /**
    * Returns the count of submitted feeds for the given optional parameters
    *
    * Optional payload parameters are:
    *
    *    FeedTypeList.Type.n                 : The feed type string, e.g. _POST_PRODUCT_DATA_
    *    FeedProcessingStatusList.Status.n   : The feed processing status string, e.g. _IN_PROGRESS_
    *    SubmittedFromDate                   : yyyy-mm-dd H:i:s
    *    SubmittedToDate                     : yyyy-mm-dd H:i:s
    *
    * @param array $arrPayloadConfig
    * @return \SimpleXMLElement
    */
   public function getFeedSubmissionCount($arrPayloadConfig = array()) {
      $this->arrMessagePayload['Action'] = 'GetFeedSubmissionCount';
      return $this->processPayload($arrPayloadConfig);
   }

}