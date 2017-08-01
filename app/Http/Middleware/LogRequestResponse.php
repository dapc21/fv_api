<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

class LogRequestResponse
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        //Loguear peticion
        Log::info('Request - '.$request->getMethod().' '.$request->getUri());

        $inputs = $request->all();
        foreach ($inputs as $key => $input) {
            if(is_array($input)){
                Log::info('Request - Input - '.$key.': '.json_encode($input));
            }
            else {
                Log::info('Request - Input - '.$key.': '.$input);
            }

        }

        $content = $request->getContent();
        Log::info('Request - Content - '.$content);

        $headers = $request->headers;
        foreach ($headers as $headerName => $headerValue) {
            Log::info('Request - Header - '.$headerName.': '.implode("",$headerValue));
        }
		
        $files = $request->files;
        foreach ($files  as $key => $file) {
			Log::info('Request - File - '.$key);
		}			

        //Loguear respuesta
        Log::info('Response - '.$response->getStatusCode().' '.$response->getContent());
    }
}
