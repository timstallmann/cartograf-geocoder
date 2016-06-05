<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 2/7/16
 * Time: 9:11 AM
 */

namespace Cartograf\GeoCoder;

abstract class GeoCoder {
  protected $curlHandle;
  protected $apiKey = FALSE;
  protected $baseUrl = '';
  public $threshold = 0.5;

  abstract protected function getQueryString(Address $address);
  abstract protected function getQueryStringFromRawText($location);
  abstract protected function getLatLngFromResult(\stdClass $result);

  public function __construct($ch) {
    $this->curlHandle = $ch;
  }

  public function getBaseUrl() {
    if ($this->baseUrl <> '') {
      return $this->baseUrl;
    }
    else {
      return FALSE;
    }
  }

  public function setBaseUrl($url) {
    $this->baseUrl = $url;
  }

  public function setApiKey($api_key) {
    $this->apiKey = $api_key;
  }

  /**
   * Geocode a given address.
   *
   * @param \Cartograf\GeoCoder\Address $address
   *   Address object with fields specified, or NULL if using location.
   * @param null $location
   *   Raw string location with no fields specified.
   *
   * @return bool
   */
  public function geoCode(Address $address = NULL, $location = NULL) {
    if ($address !== NULL) {
      $query_string = $this->getQueryString($address);
    }
    else {
      $query_string = $this->getQueryStringFromRawText($location);
    }
    if ($this->getBaseUrl() && $query_string) {
      $url = $this->getBaseUrl() . $query_string;
      curl_setopt($this->curlHandle, CURLOPT_URL, $url);
      curl_setopt($this->curlHandle, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($this->curlHandle, CURLOPT_SSL_VERIFYPEER, FALSE);

      $result = json_decode(curl_exec($this->curlHandle)) or trigger_error(curl_error($this->curlHandle));

      if ($result) {
        return $this->getLatLngFromResult($result);
      }
    }
    else {
      return FALSE;
    }
  }
}