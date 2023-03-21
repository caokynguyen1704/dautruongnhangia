<?php
$ref = "1425117254 b10b94c  (tag: v150228_2100.02)";
if(preg_match("!\(tag: (v[0-9]{6,}_[0-9]{4,}(\.[0-9]{2}){0,})\)!", $ref, $matches)){
    print_r($matches);
}
