<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
    	if (Session::has('locale')) {
    		$locale = Session::get('locale', Config::get('app.locale'));
    	} else {
    		$locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
    	
    		if ($locale != 'es' && $locale != 'en') {
    			$locale = Config::get('app.locale');
    		}
    	}
    	App::setLocale($locale);
    	return $next($request);
    }
}
