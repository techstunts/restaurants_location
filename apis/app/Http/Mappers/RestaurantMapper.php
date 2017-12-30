<?php
namespace App\Http\Mappers;

use App\Restaurant;
use Illuminate\Support\Facades\DB;

class RestaurantMapper
{
    private  $RADIUS_OF_EARTH = 6371;

    /**
     * Searches for restaurants around given latitude and longitude within given radius
     *
     * @param float $lat <p>
     * Latitude to search around</p>
     * @param float $lon <p>
     * Longitude to search around</p>
     * @param float $rad [optional] <p>
     * Radius in Kilometers</p>
     *
     * @return ModelCollection Restaurants fetchedfrom database
     */
    public function getRestaurantsByLatLon($lat, $lon, $rad = 5)
    {
        $scaling = 10000000;

        $R = $this->RADIUS_OF_EARTH;  // earth's mean radius, km

        // first-cut bounding box (in degrees)
        $maxLat = ($lat + rad2deg($rad / $R))*$scaling;
        $minLat = ($lat - rad2deg($rad / $R))*$scaling;
        $maxLon = ($lon + rad2deg(asin($rad / $R) / cos(deg2rad($lat))))*$scaling;
        $minLon = ($lon - rad2deg(asin($rad / $R) / cos(deg2rad($lat))))*$scaling;

        $lat = deg2rad($lat);
        $lon = deg2rad($lon);

        $results =  Restaurant::select('id', 'name', 'lat_d as lat','lon_d as lon',
            DB::raw("(acos(sin($lat)*sin(radians(lat_d)) + cos($lat)*cos(radians(lat_d))*cos(radians(lon_d)-$lon)) * $R) As distance")
            )
            ->whereBetween('lat', [$minLat, $maxLat])
            ->whereBetween('lon', [$minLon, $maxLon])
            ->where(DB::raw("(acos(sin($lat)*sin(radians(lat_d)) + cos($lat)*cos(radians(lat_d))*cos(radians(lon_d)-$lon)) * $R)"), "<", $rad)
            ->orderBy('distance')
            ->limit(20)
            ->get();

        return $results;

    }

}