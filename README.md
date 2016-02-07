# cartograf/geocoder

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

This is a bare-bones geocoding framework using cUrl to make http requests. I wrote it for myself as a utility class, mainly for
geocoding from the command line.

It's intended to be easily extensible for custom geocoding APIs and to get out of the way as much as possible.
`willdurand/geocoder` might be a better framework for you if you want something more robust or complex.

## Install

Via Composer

``` bash
$ composer require cartograf/geocoder
```

## Usage

You'll need a class which extends the abstract `Geocoder` base class and defines the following methods:

* `getQueryString(Address $address)` converts an `Address` into the portion of the query string which follows the `baseUrl`. Note that for now this needs to specify JSON output as well.
* `getBaseUrl()` - defaults to `$this->baseUrl` if that is set via `setBaseUrl()`
* `getLatLngFromResult(\stdClass $result)` consumes the json-decoded result object and returns an object with `latitude` and `longitude` properties. Note that this is the final return value so you could add other properties as well if you need them.
 
Classes implementing MapQuest Nominatim (just set API key) and geocoding via an AWS EC2 instance of [Geolytica's geocoder](https://aws.amazon.com/marketplace/pp/B013CW6HOA) are included.

``` php
$ch = curl_init() or die("curl_init failed");
$geocoder = new Cartograf\Geocoder($ch);
echo $geocoder->geoCode($address);
curl_close($ch);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

Not yet implemented.

``` bash
$ composer test
```

## Contributing

Feel free to extend this and submit PRs! I welcome contributions.

## Credits

- [Tim Stallmann][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/cartograf/geocoder.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/cartograf/geocoder/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cartograf/geocoder.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cartograf/geocoder.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/cartograf/geocoder.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/cartograf/geocoder
[link-travis]: https://travis-ci.org/cartograf/geocoder
[link-scrutinizer]: https://scrutinizer-ci.com/g/cartograf/geocoder/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cartograf/geocoder
[link-downloads]: https://packagist.org/packages/cartograf/geocoder
[link-author]: https://github.com/timstallmann
[link-contributors]: ../../contributors
