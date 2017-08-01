<?php

namespace App\Console\Commands;

use App\datatraffic\lib\Util;
use App\Http\Controllers\Forms\RegisterController;
use App\Models\Forms\Form;
use App\Models\Forms\Register;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Log;

class ExportFormRegisters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportFormRegisters {email} {idCompany} {idFormType} {filters?} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export to CSV form's registers";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Util::$manageAllCompanies = true;
        Util::$manageAllResource = true;

        $to = $this->argument('email');
        $idCompany = $this->argument('idCompany');
        $id_formType = $this->argument('idFormType');

        $strFilters = $this->argument('filters');
        Log::info($strFilters);
        $filters = json_decode($strFilters,true);

        $controller = new RegisterController();
        $parentModel = new Register();
        $query = $parentModel->newQueryWithoutScopes();

        //Filtramos
        if (!empty($filters)) {
            $query = $controller->filters( $parentModel, $query, $filters );
        }
        $query->where('id_company', ['$ref' => 'companies', '$id' => new ObjectID($idCompany)]);

        //Ordenamos
        if (!empty($sorts)){
            $query = $controller->orders($parentModel, $query, $sorts);
        }

        //Recuperar tipo de formulario y sus columnas
        $headers = [];
        $headers['id'] = 'ID';
        $headers['login'] = 'Recurso';
        $headers['code'] = 'Codigo';
        $headers['name'] = 'Nombre';
        $headers['address'] = 'Direccion';
        $headers['arrival_time'] = 'Fecha inicio programada';
        $headers['finish_time'] = 'Fecha fin programada';
        $headers['status'] = 'Estado';

        $columnas = [];
        $formTypeObject = new Form();
        $formTypeQuery = $formTypeObject->newQueryWithoutScopes();
        $formType = $formTypeQuery->where('_id',new ObjectID($id_formType))->first();
        if($formType)
        {
            $sections = $formType->sections;
            foreach ($sections as $section) {
                $questions = $section['questions'];

                foreach ($questions as $question) {
                    $columnas[$question->cid] = "";
                    $headers[$question->cid] = $question->configuration['fieldLabel'];
                }
            }
        }

        //Crear el archivo
        $pathToFile = storage_path('app/csv/'.Util::generateRandomString().'_export.csv');

        //Escribir los headers
        $this->writeCSVFileHeader($pathToFile, $headers);

        //Escribir los registros
        //DB::connection()->enableQueryLog();
        $limit = 100;
        $totalRegistros = $query->count();
        $totalPaginas = (int)($totalRegistros/$limit) + 1;
        $page = 1;

        for ($page = 1; $page <= $totalPaginas; $page++)
        {
            //Ejecutamos la consulta configurada
            $results = $controller->paginate($parentModel, $query, $page, $limit, ['*']);
            $this->writeCSVFile($pathToFile, $columnas, $results);
        }
        //dump(DB::connection()->getQueryLog());

        $subject = '[FIELDVISION] Descarga de registros en formato CSV';
        $messageView = 'email.exportRegisters';
        $this->sendEmail($to, $subject, $messageView, $pathToFile);
    }

    private function writeCSVFileHeader($pathToFile, $headers)
    {
        $file = fopen($pathToFile, 'a');
        fputcsv($file, $headers);
        fclose($file);
    }

    private function writeCSVFile($pathToFile, $columnas, $results)
    {
        $file = fopen($pathToFile, 'a');
        foreach ($results as $result)
        {
            $row = [];
            array_push($row,(string)$result->_id);
            array_push($row,$result->login);
            $task = $result->task;
            if($task)
            {
                array_push($row,$result->task->code);
                array_push($row,$result->task->name);
                array_push($row,$result->task->address);
                array_push($row,$result->task->arrival_time);
                array_push($row,$result->task->finish_time);
                array_push($row,$result->task->status);
            }
            else{
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
            }

            //Recuperar columnas
            $dataWeb = $result->dataWeb;
            Log::info($dataWeb);
            foreach ($columnas as $key => $columna)
            {
                if(array_key_exists($key,$dataWeb)){

                    if (is_array($dataWeb[$key])) {
                        $subString = "";
                        foreach ($dataWeb[$key] as $subArray)
                        {
                            $subString .= implode(';', $subArray)."|";
                        }
                        $row[$key] = $subString;
                    } else {
                        if (is_a($dataWeb[$key], 'MongoDB\BSON\UTCDateTime')) {
                            $row[$key] = Carbon::createFromTimestamp($dataWeb[$key]->toDateTime()->getTimestamp(),'GMT-0')->toDateTimeString();
                        }
                        else {
                            $row[$key] = $dataWeb[$key];
                        }
                    }
                } else {
                    $row[$key] = "";
                }
            }
            fputcsv($file, $row);
        }

        fclose($file);
    }

    private function sendEmail($to, $subject, $messageView, $pathToFile){

        $data = [];
        Mail::send($messageView, $data, function ($message) use ($to, $subject, $pathToFile) {
            $message->from(env('MAIL_USERNAME','soporte@datatraffic.com.co'));
            $message->to($to);
            $message->subject($subject);
            $message->attach($pathToFile);
        });
    }

}
