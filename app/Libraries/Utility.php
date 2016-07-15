<?php
namespace App\Libraries;

/**
* Use for system functionality
*
*/
class Utility{

	/**
	*
	* to check the address on google maps
	* return @string
	*
	**/
	public static function CheckAddress($address){
		$gmap = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".env('GOOGLE_MAP_KEY'));
		$gmap = json_decode($gmap);
		return $gmap->status;
	}
        
        /**
         * 
         */
        public static function XMLAttribute($object, $attribute)
        {
            if(isset($object[$attribute]))
                return (string) $object[$attribute];
        }
}
