<?php
/**
 * Created by PhpStorm.
 * User: Aleksei Koltashev
 * Date: 28.05.2016
 * Time: 20:25
 */

/**
 * @param $html
 */
mb_internal_encoding("UTF-8");
function HTML_to_CSV($htmlStr, $filename){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    include_once $dir . '/includes/simple_html_dom.php';
    $html = new simple_html_dom();
    $html->load($htmlStr);
    $arrTr = $html->find('td');
    $filecsv = fopen($dir . '/gen_files/' . iconv("utf-8","windows-1251" , $filename) . '.csv','w');
    for($i=0; $i < count($arrTr); $i+=2){
        fwrite($filecsv, iconv("utf-8","windows-1251",$arrTr[$i]->plaintext) . ';');
        fwrite($filecsv,iconv("utf-8","windows-1251",$arrTr[$i+1]->plaintext));
        fwrite($filecsv, PHP_EOL);
    }

    fclose($filecsv);
    return './gen_files/' . iconv("utf-8","windows-1251" , $filename) . '.csv';
}
