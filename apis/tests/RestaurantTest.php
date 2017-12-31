<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Http\Mappers\RestaurantMapper;
use App\Restaurant;

class RestaurantTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testJaipur()
    {
        $restaurantMapper = new RestaurantMapper();
        $results = $restaurantMapper->getRestaurantsByLatLon("26.9124", "75.7873", 50);

        $this->assertEmpty(
            $results
        );
    }
    public function testMohaliStadium()
    {
        $restaurantMapper = new RestaurantMapper();
        $results = $restaurantMapper->getRestaurantsByLatLon("30.6909","76.7375",5);

        $this->assertNotEmpty(
            $results
        );
    }

    public function testRestaurantsNearMohaliStadium()
    {
        $restaurantMapper = new RestaurantMapper();
        $results = $restaurantMapper->getRestaurantsByLatLon("30.6909","76.7375",5);

        $restaurant_names = [];
        foreach($results as $restaurant){
            $restaurant_names[] = $restaurant->name;
        }

        $this->assertTrue(
            in_array("Exotic Restaurant", $restaurant_names)
        );

        $this->assertFalse(
            in_array("Walia Chicken Corner", $restaurant_names)
        );
    }

    public function testRestaurantsNearMaxHospital()
    {
        $restaurantMapper = new RestaurantMapper();
        $results = $restaurantMapper->getRestaurantsByLatLon("30.7400","76.7143",5);

        $restaurant_names = [];
        foreach($results as $restaurant){
            $restaurant_names[] = $restaurant->name;
        }

        $this->assertFalse(
            in_array("Exotic Restaurant", $restaurant_names)
        );

        $this->assertTrue(
            in_array("Walia Chicken Corner", $restaurant_names)
        );
    }

}
