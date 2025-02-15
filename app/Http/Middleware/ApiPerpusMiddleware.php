<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Petugas;
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
        app()->setLocale('id');
        $token = $request->header('Authorization');
    
        \Log::info('Token diterima di backend:', ['token' => $token]);

        if ($token && str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7); // Ambil token tanpa "Bearer "
           
        }


        $authenticate = true;

        if(!$token){
            $authenticate = false;
            Log::error('No token provided in Authorization header.');
        }

     
        $petugas = Petugas::where('token' , $token)->first();
       

        if(!$petugas){
            $authenticate = false;
            Log::warning('Petugas not found for token:', ['token' => $token]);
        } else {
            Log::info('Petugas ditemukan', ['petugas' => $petugas]);
            Auth::login($petugas);

            
        }
             
                
        if($authenticate){
            return $next($request);
        }else{
            Log::error('Authentication failed for token:', ['token' => $token]);
            return response()->json([
                'errors' => [
                    "message" => [
                        "Tidak ter-authentikasi"
                    ]
                ]
            ])->setStatusCode(401);
        }

       
    }
}
