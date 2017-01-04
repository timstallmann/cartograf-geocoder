<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 2/7/16
 * Time: 9:13 AM
 */

namespace Cartograf\GeoCoder;

/**
 * Class Google
 * @package Cartograf\GeoCoder
 *
 * Extension of the base GeoCoder class for use with Google Maps API.
 */
class Google extends GeoCoder {
  protected $apiKey;

  // Array of valid location types.
  protected $validLocationTypes;

  public function __construct($ch) {
    parent::__construct($ch);
    $this->validLocationTypes = array('street_address', 'route', 'intersection', 'premise', 'subpremise');
  }

  public function setMinGranularity($min_granularity) {
  }

  public function getBaseUrl() {
    if ($this->apiKey) {
      return 'https://maps.googleapis.com/maps/api/geocode/json?key=' . $this->apiKey;
    }
    else {
      return FALSE;
    }
  }

  protected function getQueryStringFromRawText($location) {
    if ($location !== FALSE && $location !== '') {
      return '&address=' . rawurlencode($location);
    }
    else {
      return FALSE;
    }
  }

  protected function getQueryString(Address $address) {
    $base = $this->getQueryStringFromRawText(sprintf("%s, %s, %s %s", $address->street, $address->city, $address->state, $address->postalCode));
    $return = '';
    if ($address->country) {
      $return .= '&components=country:' . rawurlencode($address->country);
    }
    if ($base) {
      return $base . $return;
    }
    return FALSE;
  }

  protected function getLatLngFromResult(\stdClass $result) {
    $return = FALSE;
    if ($result) {
      if (property_exists($result, "status") && $result->status == "OK") {
        if (count(array_intersect($result->results[0]->types, $this->validLocationTypes)) > 0) {
          $return = new LatLng;
          $return->latitude = $result->results[0]->geometry->location->lat;
          $return->longitude = $result->results[0]->geometry->location->lng;
        }
      }
    }

    return $return;
  }
}
