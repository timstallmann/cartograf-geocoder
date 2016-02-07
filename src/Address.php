<?php

namespace Cartograf\GeoCoder;

class Address {
  public $street;
  public $city;
  public $state;
  public $postalCode;
  public $country;

  public function __construct($street, $city, $state, $postal_code, $country) {
    $this->street = $street;
    $this->city = $city;
    $this->state = $state;
    $this->postalCode = $postal_code;
    $this->country = $country;
  }
}