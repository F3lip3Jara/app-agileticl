<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonResponseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        if ($response->headers->get('Content-Type') === 'application/json') {
            $content = $response->getContent();
            
            // Eliminar cualquier carácter no deseado antes del JSON
            $content = preg_replace('/^[^{[\]}\s"\']+/', '', $content);
            
            // Asegurarse de que el contenido es JSON válido
            if (json_decode($content) === null && json_last_error() !== JSON_ERROR_NONE) {
                // Si no es JSON válido, intentar limpiar más agresivamente
                $content = preg_replace('/[^{}\[\],:\"\'0-9a-zA-Z\s_-]/', '', $content);
            }
            
            $response->setContent($content);
        }
        
        return $response;
    }
} 