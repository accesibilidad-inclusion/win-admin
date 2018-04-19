<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class JsonContentType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Content-Type', 'application/json');
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Headers', 'Authorization, Content-Type');
        switch ( $response->getStatusCode() ) {
            case 404:
                $status_text = $this->getStatusText( $response->getStatusCode() );
                $response->setContent( json_encode( $status_text ) );
                break;
            case 500:
                $exception = json_decode( $response->getContent() );
                unset( $exception->trace, $exception->file, $exception->line );
                $response->setContent( json_encode( $exception ) );
                break;
        }
        return $response;
    }
    private function getStatusText( $code )
    {
        return Response::$statusTexts[ $code ] ?? '';
    }
}
