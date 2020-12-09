<?php
namespace App\Http\Middleware;
use Closure;

class SecureHeaders
{
    // Enumerate headers which you do not want in your application's responses.
    // Great starting point would be to go check out @Scott_Helme's:
    // https://securityheaders.com/
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];
    
    public function handle($request, Closure $next)
    {
        $this->removeUnwantedHeaders($this->unwantedHeaderList);
        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        // $response->headers->set('Content-Security-Policy', "default-src 'none'; font-src 'self' https://fonts.gstatic.com; form-action 'self'; img-src 'self' https://storage.googleapis.com; script-src 'strict-dynamic' 'nonce-yGqNFjMh' 'unsafe-inline' https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/; require-trusted-types-for 'script'; base-uri https://microblogs.nad-a4.ca");
        return $response;
    }
    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header)
            header_remove($header);
    }
}
?>