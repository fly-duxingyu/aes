<?php

namespace Duxingyu\Aes\Middleware;

use Closure;
use Duxingyu\Aes\Php\Aes;
use ErrorException;
use Illuminate\Http\Request;

class AesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ErrorException
     */
    public function handle(Request $request, Closure $next)
    {
        $params = $request->input('params');
        $data = Aes::init()->decrypt($params);
        $request->merge(json_decode($data, true));
        return $next($request);
    }
}
