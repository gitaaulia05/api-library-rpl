<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiPerpusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->header('Authorization');
     
        $authenticate = true;

        if(!$token){
            $authenticate = false;

            Log::warning('Authorization token missing');
        }
   
        $petugas = petugas::where('token' , $token)->first();
        Log::info('Petugas found:', ['petugas' => $petugas]); 

        if(!$petugas){
            $authenticate = false;
            Log::warning('Petugas not found for token:', ['token' => $token]);
        } else {
            Auth::login($petugas);
        }
             
                
        if($authenticate){
            return $next($request);
        }else{
            Log::error('Authentication failed for token:', ['token' => $token]);
            return response()->json([
                'errors' => [
                    "message" => [
                        "unathorize"
                    ]
                ]
            ])->setStatusCode(401);
        }

       
    }
}
