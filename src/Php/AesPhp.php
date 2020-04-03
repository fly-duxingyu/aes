<?php


namespace Aes\Php;


use ErrorException;

class AesPhp
{
    private static $_aes_Obj;
    private $_string = 'ABCDEFGHIGKLMNOPQRSTWVUXYZabcdefghigklmnopqrstwvuxyz0123456789=';
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secret_key;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv;

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options;
    protected $tag;

    /**
     * 构造函数
     *
     * @param string $key 密钥
     * @param string $method 加密方式
     * @param string $iv iv向量
     * @param mixed $options 还不是很清楚
     *
     * @throws Exception
     * @throws \Exception
     */
    public function __construct($key, $method = 'AES-128-CBC', $options = 0, $iv = '')
    {
        if (in_array(strtolower($method), openssl_get_cipher_methods())) {
            $length = openssl_cipher_iv_length($method);//获取iv的长度 随机生成 iv字符串
            $this->iv = substr(str_shuffle($this->_string), mt_rand(0, strlen($this->_string) - $length), $length);
        } else {
            throw new ErrorException('加密类型错误');
        }
        // key是必须要设置的
        $this->secret_key = isset($key) ? $key : 'c2rFIU3ym8AXJ1aU';

        $this->method = $method;
        $this->options = $options;
    }

    public static function init($key)
    {
        if (!self::$_aes_Obj instanceof Self) {
            self::$_aes_Obj = new self($key);
        }
        return self::$_aes_Obj;
    }

    /**
     * 加密方法，对数据进行加密，返回加密后的数据
     *
     * @param string $data 要加密的数据
     *
     * @return string
     *
     * @throws ErrorException
     */
    public function encrypt($data)
    {
        $data = is_array($data) ? json_encode($data) : $data;
        $value = openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
        if ($value === false) {
            throw new ErrorException('Could not encrypt the data.');
        }
        $iv = $this->iv;
        $json = json_encode(compact('iv', 'value'));
        return base64_encode($json);
    }

    /**
     * 解密方法，对数据进行解密，返回解密后的数据
     *
     * @param string $data 要解密的数据
     *
     * @return string
     *
     */
    public function decrypt($data)
    {
        $payload = json_decode(base64_decode($data), true);

        $data = \openssl_decrypt(
            $payload['value'], $this->method, $this->secret_key, $this->options, $payload['iv']
        );
        return is_string($data) ? json_decode($data, true) : $data;
    }
}