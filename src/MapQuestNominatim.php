<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 2/7/16
 * Time: 9:13 AM
 */

namespace Cartograf\GeoCoder;

use Cartograf\GeoCoder\Geocoder;

class MapQuestNominatim extends GeoCoder {
  protected $apiKey;

  public function getBaseUrl() {
    if ($this->apiKey) {
      return 'http://open.mapquestapi.com/geocoding/v1/address?key=' . $this->apiKey;
    }
    else {
      return FALSE;
    }
  }

  protected function getQueryStringFromRawText($location) {
    $base = '&outFormat=json&thumbMaps=false&maxResults=1';
    if ($location !== FALSE && $location !== '') {
      return $base . '&location=' . rawurlencode($location);
    }
    else {
      return FALSE;
    }
  }

  protected function getQueryString(Address $address) {
    $base = '&outFormat=json&thumbMaps=false&maxResults=1';
    $return = '';
    if ($address->street) {
      $return .= '&street=' . rawurlencode($address->street);
    }
    if ($address->city) {
      $return .= '&city=' . rawurlencode($address->city);
    }
    if ($address->state) {
      $return .= '&state=' . rawurlencode($address->state);
    }
    if ($address->postalCode) {
      $return .= '&postalCode=' . rawurlencode($address->postalCode);
    }
    if ($address->country) {
      $return .= '&country=' . rawurlencode($address->country);
    }
    if ($return <> '') {
      return $base . $return;
    }
    return FALSE;
  }

  protected function getLatLngFromResult(\stdClass $result) {
    $return = new LatLng;
    if ($result && property_exists($result, "results") && is_array($result->results) && property_exists($result->results[0], "locations") && is_array($result->results[0]->locations)) {
      if (count($result->results[0]->locations) > 0) {
        $return->latitude = $result->results[0]->locations[0]->latLng->lat;
        $return->longitude = $result->results[0]->locations[0]->latLng->lng;
      } //valid response & locations
      else {
        return FALSE;
      } //valid response, no locations
    }
    elseif ($result) {
      $return->latitude = $result->lat;
      $return->longitude = $result->lng;
    }
    else {
      return FALSE;
    }
    return $return;
  }
}