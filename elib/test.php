
<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/simple_html_dom.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/main_data.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/dbconnect.php";


function curl_start($link)
{

    $ch = curl_init($link);

    $coo = 'cookie/coo.txt';
    $data = "";
    curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.112 Safari/537.36');
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");

    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $coo);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $coo);


    $filename = curl_exec($ch);
//Закрываем cURL-сессию
    curl_close($ch);
    return $filename;
}

$link = "http://elibrary.ru/org_profile.asp?id=7316";
$filehtml = curl_start($link);

    $html = str_get_html($filehtml);
    $table = $html->find('table[width=580]', 3);
    $name = $html->find('table[width=580]', 1)->find('font[color=#F26C4F] b', 0)->plaintext;
    $el = $table->find('td font');
    for ($i = 0; $i < count($el); $i += 2) {
       echo  trim($el[$i]->plaintext, '&nbsp;') . ' ' . trim($el[$i + 1]->plaintext, '&nbsp;') . '<br/>';
    }

$link = "http://elibrary.ru/org_profile2_citednum.asp?id=7316";
$filehtml = curl_start($link);
$html =  str_get_html($filehtml);
$allcited = 0;
$fo = $html->find('table[id=restab] tr[valign=top] td[class=midtext]');

for($i=0; $i<count($fo); $i+=2){
    $allcited += (int) trim($fo[$i]->plaintext,'&nbsp;') * (int)trim($fo[$i+1]->plaintext,'&nbsp;');
}

echo 'Всего цитирований '. $allcited;