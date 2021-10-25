<?php

namespace Duxingyu\Aes\Middleware;

use Closure;
use Duxingyu\Aes\Php\Aes;
use ErrorException;
use Illuminate\Http\Request;
use Cache;
use Config;

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
        if ($params) {
            $dataJson = Aes::init()->decrypt($params);
            $data = $dataJson ? json_decode($dataJson, true) : [];
            if (!empty($data['unique_key'])) {
                if (Cache::has($data['unique_key'])) {
                    return response(['message' => '请勿重复操作,稍后再试', 'code' => 401]);
                }
                $time = Config::get('duxingyuConfig.repeat_click_time') ?: 3;
                Cache::set($data['unique_key'], $dataJson, $time);
            }
            unset($data['unique_key']);
            $request->merge($data);
        }
        return $next($request);
    }
}
