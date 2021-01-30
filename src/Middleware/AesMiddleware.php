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
        $data = $request->all();
        if (is_array($data) && $data) {
            foreach ($data as $key => $item) {
                $data[$key] = Aes::init()->decrypt($item);
            }
            $request->merge($data);
        }
        return $next($request);
    }
}
