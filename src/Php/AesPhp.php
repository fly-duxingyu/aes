<?php


namespace Aes\Php;


use ErrorException;

class AesPhp
{
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
            $iv = random_bytes(openssl_cipher_iv_length($method));//获取iv的长度 随机生成 iv字符串
            $this->iv = $iv;
        }else{
            throw new ErrorException('加密类型错误');
        }
        // key是必须要设置的
        $this->secret_key = isset($key) ? $key : 'c2rFIU3ym8AXJ1aU';

        $this->method = $method;

        $this->options = $options;
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
        $value = openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
        if ($value === false) {
            throw new ErrorException('Could not encrypt the data.');
        }

        // Once we get the encrypted value we'll go ahead and base64_encode the input
        // vector and create the MAC for the encrypted value so we can then verify
        // its authenticity. Then, we'll JSON the data into the "payload" array.

        $json = json_encode(compact('iv', 'value'), JSON_UNESCAPED_SLASHES);

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
        $payload = $this->getJsonPayload($data);

        $iv = base64_decode($payload['iv']);

        $decrypted = \openssl_decrypt(
            $payload['value'], $this->method, $this->secret_key, $this->options, $iv
        );

        if ($decrypted === false) {
            throw new Error('Could not decrypt the data.');
        }

        return $decrypted;
    }

    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);
        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (!$this->validPayload($payload)) {
            throw new Error('The payload is invalid.');
        }

        if (!$this->validMac($payload)) {
            throw new Error('The MAC is invalid.');
        }

        return $payload;
    }

    protected function validPayload($payload)
    {
        return is_array($payload) && isset($payload['iv'], $payload['value'], $payload['mac']) &&
            strlen(base64_decode($payload['iv'], true)) === openssl_cipher_iv_length($this->method);
    }

    protected function validMac(array $payload)
    {
        $calculated = $this->calculateMac($payload, $bytes = random_bytes(16));

        return hash_equals(
            hash_hmac('sha256', $payload['mac'], $bytes, true), $calculated
        );
    }

    protected function calculateMac($payload, $bytes)
    {
        return hash_hmac(
            'sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true
        );
    }
}