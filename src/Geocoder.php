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

  abstract protected function getQueryString(Address $address);
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

  public function geoCode(Address $address) {
    if ($this->getBaseUrl() && $this->getQueryString($address)) {
      $url = $this->getBaseUrl() . $this->getQueryString($address);
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