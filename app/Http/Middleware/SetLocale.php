<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $arr = ['uz', 'ru', 'en'];
        $lang = $request->segment(2);
        if (in_array($lang, $arr)){
            if (!session()->get('locale') == $lang){
                session()->put('locale', $lang);
            }
        }
        app()->setLocale(session()->get('locale'));

        return $next($request);
    }
}
