<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GetTableNameFromRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uriArray = array_filter(explode('/', trim(str_replace('-', '_', $request->getRequestUri()), '/')));
        // dump($uriArray);
        if(!empty($uriArray)){
            // $table = strtolower(($uriArray[0]) . '_' . $uriArray[1]);
            $table = strtolower(($uriArray[2]) . '_' . $uriArray[3]);
            $table = str_replace('gen_tender_type', 'drk_tender_type', $table);
            // $table = str_replace('agrochemicals_', 'agri_', $table);
            // $table = preg_replace('/commodity_/', 'agri_', $table, 1);;
            // $table = str_replace('geography_', 'geo_', $table);
            // $table = str_replace('geographical_', 'geo_', $table);
            // $table = str_replace('_farmer', '', $table);
            // $table = str_replace('stress_', 'agri_', $table);
            // if($uriArray[1] == 'variety'){
            //     $table = str_replace('commodity_', 'agri_', $table);
            // }
            $request->merge(['table' => $table]);
        }
        return $next($request);
    }
}
