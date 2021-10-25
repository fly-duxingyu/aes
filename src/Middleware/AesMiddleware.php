<?php

namespace Duxingyu\Aes\Middleware;

use Closure;
use Composer\Config;
use Duxingyu\Aes\Php\Aes;
use ErrorException;
use Illuminate\Http\Request;
use Cache;

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
        //获取参数
        $params = $request->input('params');

        //解密参数
        $dataJson = Aes::init()->decrypt($params);

        //json转数组
        $data = $dataJson ? json_decode($dataJson, true) : [];
        //获取唯一的key或者token
        $unique_key = $data['unique_key'] . '$_repeat_click_time';

        //查看是否存在唯一key或者token
        $value = Cache::get($unique_key);
        //已存在同样的key和数据
        if (!empty($value) && $value == $dataJson) {
            return response(['message' => '请勿重复点击', 'code' => 401]);
        }
        //不存在重复点击 上锁 默认3秒自动解除
        $time = Config('duxingyuConfig.repeat_click_time') ?: 3;
        Cache::set($unique_key, $dataJson, $time);

        //合并post数据
        unset($data['unique_key']);
        $request->merge($data);
        return $next($request);
    }
}
