<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckMobile
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
        $userAgent = $request->header('User-Agent', '');
        $userAgentLower = strtolower($userAgent);
        
        // Detecção mais precisa de dispositivos móveis
        $isMobile = false;
        
        if ($userAgent) {
            // Padrões para dispositivos móveis
            $mobilePatterns = [
                'mobile', 'android', 'iphone', 'ipad', 'ipod',
                'blackberry', 'opera mini', 'windows phone', 'windows mobile',
                'iemobile', 'tablet', 'kindle', 'silk', 'webos'
            ];
            
            foreach ($mobilePatterns as $pattern) {
                if (str_contains($userAgentLower, $pattern)) {
                    // iPad não deve ser considerado mobile (usa desktop view)
                    if ($pattern === 'ipad') {
                        $isMobile = false;
                        break;
                    }
                    $isMobile = true;
                    break;
                }
            }
        }

        // Passa informação para as views
        view()->share('isMobile', $isMobile);

        return $next($request);
    }
}
