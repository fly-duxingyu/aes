# aes对称加密
####
配置文件
######
在config目录下创建文件 aes.php
####
```
<?php
return [
    'key' => 'nprRImwBDWQ93jz4' //加密的key
];
```
#####
不创建和配置key系统默认'robertvivi'
#
使用方法
#####
```
//加密
$as = Aes::init('c2rFIU3ym8AXJ1aU')->encrypt(json_encode($data));
//解密
Aes::init('c2rFIU3ym8AXJ1aU')->decrypt($as);

```
