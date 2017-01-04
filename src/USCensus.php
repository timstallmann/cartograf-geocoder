<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 2/7/16
 * Time: 9:13 AM
 */

namespace Cartograf\GeoCoder;

/**
 * Class USCensus
 * @package Cartograf\GeoCoder
 *
 * Extension of the base GeoCoder class for use with US Census Geocoding API.
 * More info at https://geocoding.geo.census.gov/geocoder
 */
class USCensus extends GeoCoder {

  // Benchmark dataset to use for geocoding.
  protected $benchmark;

  public function __construct($ch) {
    parent::__construct($ch);
    $this->benchmark = 'Public_AR_Current';
  }

  public function setBenchmark($benchmark) {
    $this->benchmark = $benchmark;
  }

  public function getBaseUrl() {
    return 'https://geocoding.geo.census.gov/geocoder/locations/';
  }

  protected function getQueryStringFromRawText($location) {
    if ($location !== FALSE && $location !== '') {
      return sprintf('onelineaddress?format=json&benchmark=%s&address=%s', $this->benchmark, rawurlencode($location));
    }
    else {
      return FALSE;
    }
  }

  protected function getQueryString(Address $address) {
    $base = sprintf('address?format=json&benchmark=%s', $this->benchmark);
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
      $return .= '&zip=' . rawurlencode($address->postalCode);
    }
    if ($return <> '') {
      return $base . $return;
    }
    return FALSE;
  }

  protected function getLatLngFromResult(\stdClass $result) {
    $return = FALSE;
    if ($result) {
      if (property_exists($result, "addressMatches") && count($result->addressMatches) > 0) {
        $return = new LatLng;
        $return->latitude = $result->addressMatches[0]->coordinates->y;
        $return->longitude = $result->addressMatches[0]->coordinates->x;
      }
    }

    return $return;
  }
}
