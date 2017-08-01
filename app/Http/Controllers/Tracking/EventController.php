<?php
/**
 * Created by PhpStorm.
 * User: Desarrollo
 * Date: 05/06/2016
 * Time: 05:02 PM
 */

namespace App\Http\Controllers\Tracking;


use App\datatraffic\lib\Util;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GenericMongo\DatatrafficController;
use App\Models\Tracking\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Tracking\Event';

    public function store(Request $request)
    {
        $json = $request->getContent();
        $arrayFromJson = json_decode($json, true);

        $event = new Event();
        $event->id_event = (int) round(microtime(true));
        $event->eventCategory = "Tracking";
        $event->eventType = $arrayFromJson['name'];
        $event->message = $arrayFromJson['value'];
        $event->updatetime = Carbon::createFromFormat('Y-m-d H:i:s', $arrayFromJson["date"]);
        $event->save();

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = [["reference" => $event->id_event]];
        $total = 1;
        $intCode = 201;
        $view = null;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }

    public function update (Request $request, $strId ){
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = [["reference" => $strId]];
        $total = 1;
        $intCode = 200;
        $view = null;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }

    public function delete (Request $request, $strId ){
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = [["reference" => $strId]];
        $total = 1;
        $intCode = 200;
        $view = null;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }
}