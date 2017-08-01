<?php
namespace App\Http\Controllers\Planning;

use Input;
use Illuminate\Routing\Controller as BaseController;

class AddressController extends BaseController
{
    public function validate()
    {
        $result["error"] = true;
        $result["msg"] = "Ocurrio un error";
        $result["pagination"] = array(
            "total" => 0,
            "per_page" => 15,
            "current_page" => 1,
            "last_page" => 1,
            "from" => 1,
            "to" => 1
        );
        $result["data"] = [];

        if(Input::has("address"))
        {
            $address = Input::get("address");
            $geometry = $this->geocod($address);

            if($geometry)
            {
                $result["error"] = false;
                $result["msg"] = "OK";
                $result["pagination"] = array(
                    "total" => 1,
                    "per_page" => 15,
                    "current_page" => 1,
                    "last_page" => 1,
                    "from" => 1,
                    "to" => 1
                );

                $result["data"] = array(
                    $geometry
                );
            }
            else
            {
                $result["msg"] = "No es posible geocodificar la direccion";
            }

        }

        return $result;

    }

    private function geocod($address)
    {
        $geometry = null;

        $obj = $this->geocodResponse($address);
        if($obj !== null)
        {
            $geometry = $this->processGeocodResponse($obj);
        }

        return $geometry;
    }

    private function geocodResponse($address)
    {
        $i = 1;
        $error = true;
        $obj = null;

        while($error === true && $i <= env('MAX_GEOCOD'))
        {
            $json = $this->geocodRequest($address);
            $obj = json_decode($json);
            $error = property_exists($obj, 'type');
            $i++;
        }

        return $obj;
    }

    private function processGeocodResponse($obj)
    {
        $geometry = null;

        if(is_a($obj,'stdClass'))
        {
            $response = new \stdClass;
            if(property_exists($obj, 'Response'))
            {
                $response = $obj->Response;
            }

            $viewArray = [];
            if(property_exists($response, 'View'))
            {
                $viewArray = $response->View;
            }

            $view = new \stdClass;
            if(count($viewArray) > 0)
            {
                $view = $viewArray[0];
            }

            $viewResult = [];
            if(property_exists($view, 'Result'))
            {
                $viewResult = $view->Result;
            }

            $location = new \stdClass;
            if(count($viewResult) > 0)
            {
                if(property_exists($viewResult[0], 'Location'))
                {
                    $location = $viewResult[0]->Location;
                }
            }

            $displayPosition = new \stdClass;
            if(property_exists($location, 'DisplayPosition'))
            {
                $displayPosition = $location->DisplayPosition;
            }

            $latitude = null;
            if(property_exists($displayPosition, 'Latitude'))
            {
                $latitude = $displayPosition->Latitude;
            }

            $longitude = null;
            if(property_exists($displayPosition, 'Longitude'))
            {
                $longitude = $displayPosition->Longitude;
            }

            $locationAdress = new \stdClass;
            if(property_exists($location, 'Address'))
            {
                $locationAdress = $location->Address;
            }

            $label = null;
            if(property_exists($locationAdress, 'Label'))
            {
                $label = $locationAdress->Label;
            }

            if($latitude !== null && $longitude !== null)
            {
                $geometry = new \stdClass;
                $geometry->name = $label;
                $geometry->lat = $latitude;
                $geometry->lng = $longitude;
            }
        }

        return $geometry;
    }

    private function geocodRequest($address)
    {
        $service = env('URL_GEOCOD');
        $app = env('APP_ID_GEOCOD');
        $app_code = env('APP_CODE_GEOCOD');
        $gen = env('GEN_GEOCOD');
        $searchtext = urlencode($address);
        $url = $service.'?app_id='.$app.'&app_code='.$app_code.'&gen='.$gen.'&searchtext='.$searchtext;

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ));
        // Send the request & save response to $resp
        $json = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        return $json;
    }
}
