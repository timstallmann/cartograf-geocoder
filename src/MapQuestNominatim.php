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

  // Ranked array of the possibly values of the first 2 characters of
  // geocodeQualityCode from result, ordered from most precise to least.
  protected $granularityRanking;

  // Minimum precision which will be accepted.
  protected $minGranularity;

  public function __construct($ch) {
    parent::__construct($ch);
    $this->granularityRanking = array_flip(array('P1', 'L1', 'I1', 'B1', 'B2', 'B3', 'Z4', 'Z3', 'Z2', 'A6', 'Z1', 'A5', 'A4', 'A3', 'A2', 'A1'));
    $this->minGranularity = 'A6';
  }

  public function setMinGranularity($min_granularity) {
    $this->minGranularity = $min_granularity;
  }

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
    $return = FALSE;
    if ($result) {
      if (property_exists($result,
          "results") && is_array($result->results) && property_exists($result->results[0],
          "locations") && is_array($result->results[0]->locations)
      ) {
        if (count($result->results[0]->locations) > 0) {
          foreach ($result->results[0]->locations as $location) {
            if ($this->granularityRanking[substr($location->geocodeQualityCode,
                0,
                2)] <= $this->granularityRanking[$this->minGranularity]
            ) {
              $return = new LatLng;
              $return->latitude = $result->results[0]->locations[0]->latLng->lat;
              $return->longitude = $result->results[0]->locations[0]->latLng->lng;
              break;
            }
          }
        }
      }
      elseif (!empty($result->lat) && !empty($result->lng)) {
        $return = new LatLng;
        $return->latitude = $result->lat;
        $return->longitude = $result->lng;
      }
    }

    return $return;
  }
}