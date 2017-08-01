<?php

namespace App\Exceptions;

use App\datatraffic\lib\Util;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use MongoDB\Exception\RuntimeException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $error = true;
        $total = 0;
        $data = [];
        $view = null;

        if ($e instanceof AuthenticationException) {
            $intCode = 401;
            $msg = $e->guard();
            $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
            return response($result, $intCode);
        }
        else if ($e instanceof TokenExpiredException) {
            $intCode = 401;
            $msg = $e->getMessage();
            $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
            return response($result, $intCode);
        }
        else if ($e instanceof RuntimeException) {
        	$intCode = 500;
        	$msg = $e->getMessage();
        	$result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        	return response($result, $intCode);
        }
        else if ($e instanceof TokenInvalidException) {
            $intCode = 400;
            $msg = $e->getMessage();
            $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
            return response($result, $intCode);
        }
        else {
            //Verificar si el usuario tiene permiso para ver errores.
            if(!is_null(Util::$insUser))
            {
                if(!Util::$viewErrors)
                {
                    $error = false;
                    $msg = trans('general.MSG_OK');
                    $data = [];
                    $total = 0;
                    $intCode = 200;
                    $view = [];

                    $method = $request->getMethod();
                    switch ($method) {
                        case 'POST':
                            $intCode = 201;
                            break;
                        default:
                            break;
                    }

                    $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

                    return response($result, $intCode);
                }
            }

            if ($e instanceof ValidationException) {
                $intCode = 422;
                $msg = $e->validator->getMessageBag();
                $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
                return response($result, $intCode);
            } else if ($e instanceof ModelNotFoundException) {
                $intCode = 404;
                $msg = $e->getMessage();
                $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
                return response($result, $intCode);
            } else if ($e instanceof JWTException) {
                $intCode = 500;
                $msg = $e->getMessage();
                $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
                return response($result, $intCode);
            } else if ($e instanceof MissingRequestParameter) {
                $intCode = 500;
                $msg = $e->getMessage();
                $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
                return response($result, $intCode);
            } else if ($e instanceof AccessDeniedHttpException) {
                $intCode = 403;
                $msg = $e->getMessage();
                $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
                return response($result, $intCode);
            }
            else {
                $intCode = 500;
                $msg = $e->getMessage();
                $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
                return response($result, $intCode);
            }
        }
        //return parent::render($request, $e);
    }
}
