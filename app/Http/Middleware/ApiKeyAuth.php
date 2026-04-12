<?php
namespace App\Http\Middleware;

use App\Http\Controllers\BaseApiController;
use Symfony\Component\HttpFoundation\Response;
use Closure;
use Request;
use App\Helpers\FirestoreHelper;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {
        $globalSettings = FirestoreHelper::getDocument('settings/globalSettings');
    
        if(empty($request->header('apikey')) || empty($globalSettings['apiSecureKey']) || $request->header('apikey') != $globalSettings['apiSecureKey']){

            return response()->json([
                'data' => [
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Unauthorized',
                    'error' => '',
                ],
            ], Response::HTTP_UNAUTHORIZED);
            
        }
        return $next($request);
    }
}