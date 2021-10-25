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
        $dataJson = Aes::init()->decrypt($params);
        $data = $dataJson ? json_decode($dataJson, true) : [];
        $request->merge($data);
        return $next($request);
    }
}
