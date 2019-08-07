<?php

$link = 'http://cyberleninka.ru/search#';

$curl = curl_init($link);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2687.0 Safari/537.36 OPR/38.0.2205.0 (Edition developer)');
curl_setopt($curl, CURLOPT_POSTFIELDS, 'query="'. 'Южно-уральский институт управления и экономики' .'"&terms=&catalogs=&page=' . 1);
$out = curl_exec($curl);
curl_close($curl);

//echo $out;
$json = json_decode($out, true);
foreach ($json['articles']['aggs']['catalogs'] as $item){
    echo $item['name'] . ' ' . $item['count'] . '<br/>';
};
