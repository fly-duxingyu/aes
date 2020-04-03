<?php
$strs="ABCDEFGHIJKLMNOPQRSTWVUXYZabcdefghijklmnopqrstwvuxyz0123456789";
 $name=substr(str_shuffle($strs),mt_rand(0,strlen($strs)-17),16);
