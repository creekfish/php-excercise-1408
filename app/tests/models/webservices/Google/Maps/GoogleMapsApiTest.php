<?php

namespace Creekfish\Tests\Models\Webservices\Google\Maps;

use Creekfish\Models\Coordinate;
use Creekfish\Models\Webservices\Google\Maps\GoogleMapsApi;
use Creekfish\Models\Webservices\Http\HttpMethod;
use Creekfish\Models\Webservices\Http\HttpResponse;
use Creekfish\Models\Webservices\RestApiDriverInterface;
use Creekfish\Models\Webservices\RestApiResponseType;
use Phake;

class GoogleMapsApiTest extends \TestCase {

    /**
     * @var GoogleMapsApi
     */
    private $sut;

    /**
     * @var RestApiDriverInterface
     * @Mock
     */
    private $driver;

    /**
     * @var Coordinate
     * @Mock
     */
    private $coordinate;

    /**
     * @var HttpResponse
     * @Mock
     */
    private $httpResponse;

    public function setUp() {
        Phake::initAnnotations($this);
        \App::instance('Creekfish\Models\Coordinate', $this->coordinate);
        $this->sut = new GoogleMapsApi($this->driver);
    }


    /**
     * @test
     */
    public function geoCodeAddress_builds_coordinate_from_api_result() {
        Phake::when($this->driver)->restCall(Phake::anyParameters())->thenReturn($this->httpResponse);
        Phake::when($this->httpResponse)->getContent()->thenReturn($this->getGeoCodeFixture());
        $this->sut->geoCodeAddress('my address');
        Phake::verify($this->coordinate)->fromArray(array('lat' => 38.8630091, 'lng' => -94.6760395));
    }

    /**
     * @test
     */
    public function geoCodeAddress_calls_geocode_api_and_specifies_json_response_type() {
        Phake::when($this->driver)->restCall(Phake::anyParameters())->thenReturn($this->httpResponse);
        Phake::when($this->httpResponse)->getContent()->thenReturn($this->getGeoCodeFixture());
        $this->sut->geoCodeAddress('my address');
        Phake::verify($this->driver)->restCall(
            '/maps/api/geocode/json',
            array('address' => 'my address'),
            new HttpMethod(HttpMethod::GET),
            'http://maps.googleapis.com'
        );
    }

    /**
     * @test
     */
    public function geoCodeAddressParts_calls_api_with_address_built_from_all_parts() {
        Phake::when($this->driver)->restCall(Phake::anyParameters())->thenReturn($this->httpResponse);
        Phake::when($this->httpResponse)->getContent()->thenReturn($this->getGeoCodeFixture());
        $this->sut->geoCodeAddressParts('street', 'city', 'state', 'zip', 'country');
        Phake::verify($this->driver)->restCall(
            '/maps/api/geocode/json',
            array('address' => 'street city, state zip country'),
            new HttpMethod(HttpMethod::GET),
            'http://maps.googleapis.com'
        );
    }

    /**
     * @test
     */
    public function geoCodeAddressParts_calls_api_with_address_built_from_city_state_only() {
        Phake::when($this->driver)->restCall(Phake::anyParameters())->thenReturn($this->httpResponse);
        Phake::when($this->httpResponse)->getContent()->thenReturn($this->getGeoCodeFixture());
        $this->sut->geoCodeAddressParts(null, 'city', 'state', null, null);
        Phake::verify($this->driver)->restCall(
            '/maps/api/geocode/json',
            array('address' => 'city, state'),
            new HttpMethod(HttpMethod::GET),
            'http://maps.googleapis.com'
        );
    }

    /**
     * @test
     */
    public function geoCodeAddressParts_calls_api_with_address_built_from_city_state_zip_only() {
        Phake::when($this->driver)->restCall(Phake::anyParameters())->thenReturn($this->httpResponse);
        Phake::when($this->httpResponse)->getContent()->thenReturn($this->getGeoCodeFixture());
        $this->sut->geoCodeAddressParts(null, 'city', 'state', 'zip', null);
        Phake::verify($this->driver)->restCall(
            '/maps/api/geocode/json',
            array('address' => 'city, state zip'),
            new HttpMethod(HttpMethod::GET),
            'http://maps.googleapis.com'
        );
    }

    /**
     * @test
     */
    public function geoCodeAddressParts_calls_api_with_address_built_from_street_city_state_zip_only() {
        Phake::when($this->driver)->restCall(Phake::anyParameters())->thenReturn($this->httpResponse);
        Phake::when($this->httpResponse)->getContent()->thenReturn($this->getGeoCodeFixture());
        $this->sut->geoCodeAddressParts('street', 'city', 'state', 'zip', null);
        Phake::verify($this->driver)->restCall(
            '/maps/api/geocode/json',
            array('address' => 'street city, state zip'),
            new HttpMethod(HttpMethod::GET),
            'http://maps.googleapis.com'
        );
    }

    private function getGeoCodeFixture() {
        return '{
   "results" : [
      {
         "address_components" : [
            {
               "long_name" : "66223",
               "short_name" : "66223",
               "types" : [ "postal_code" ]
            },
            {
               "long_name" : "Overland Park",
               "short_name" : "Overland Park",
               "types" : [ "locality", "political" ]
            },
            {
               "long_name" : "Johnson County",
               "short_name" : "Johnson County",
               "types" : [ "administrative_area_level_2", "political" ]
            },
            {
               "long_name" : "Kansas",
               "short_name" : "KS",
               "types" : [ "administrative_area_level_1", "political" ]
            },
            {
               "long_name" : "United States",
               "short_name" : "US",
               "types" : [ "country", "political" ]
            }
         ],
         "formatted_address" : "Overland Park, KS 66223, USA",
         "geometry" : {
            "bounds" : {
               "northeast" : {
                  "lat" : 38.8878356,
                  "lng" : -94.64715099999999
               },
               "southwest" : {
                  "lat" : 38.825458,
                  "lng" : -94.686526
               }
            },
            "location" : {
               "lat" : 38.8630091,
               "lng" : -94.6760395
            },
            "location_type" : "APPROXIMATE",
            "viewport" : {
               "northeast" : {
                  "lat" : 38.8878356,
                  "lng" : -94.64715099999999
               },
               "southwest" : {
                  "lat" : 38.825458,
                  "lng" : -94.686526
               }
            }
         },
         "types" : [ "postal_code" ]
      }
   ],
   "status" : "OK"
}';
    }

}