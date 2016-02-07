<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 2/7/16
 * Time: 9:13 AM
 */

namespace Cartograf\GeoCoder;

class AWS extends GeoCoder {
  protected function getQueryString(Address $address) {
    $full_address = rawurlencode($address->street) . "," . rawurlencode($address->city) . "," . rawurlencode($address->state) . "," . rawurlencode($address->postalCode) . "," . rawurlencode($address->country);
    return $full_address . '?json=1';
  }

  protected function getLatLngFromResult(\stdClass $result) {
    if ($result && property_exists($result, 'standard') && $result->standard->confidence >= 0.75) {
      $ret = new LatLng;
      $ret->longitude = $result->longt;
      $ret->latitude = $result->latt;
      return $ret;
    }
    else {
      return FALSE;
    }
  }
}