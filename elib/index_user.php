<?php

function curl_start($link)
{
    $ch = curl_init($link);
    $coo = 'coo.txt';

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

    $fp = curl_exec($ch);
//Закрываем cURL-сессию
    curl_close($ch);
    return $fp;
}

/**
 * @param PDO $pdo
 * @param int $ref_id
 * @param int $sid
 */
function set_years($pdo, $ref_id, $sid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    include_once $dir . "/includes/simple_html_dom.php";
    include_once $dir . "/includes/dbconnect.php";
    $link = 'http://elibrary.ru/author_profile_new_years.asp?id=' . $ref_id;
    $fp = curl_start($link);
    $html = str_get_html($fp);
    $arryears = array();
    $fo = $html->find('table[id=restab] tr[valign=top] td[class=midtext]');

    for($i=0; $i<count($fo); $i+=2){
        $arryears[trim($fo[$i]->plaintext,'&nbsp;')] = trim($fo[$i+1]->plaintext,'&nbsp;');
    }

//    foreach($arryears as $key => $item){
//            echo $key . ' ' . $item . '<br>';
//    }

    try{
        $sql = 'INSERT INTO years_art SET 
                service = "elib",
                sid = :sid,
                years = :years,
                countart = :countart';
        foreach($arryears as $key => $item){

            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':years',  $key);
            $s->bindValue(':countart', $item);
            $s->execute();
        }


    }catch (PDOException $e) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/error_db.php";
        set_error("Ошибка бд при обработке setYearsUser", $e);
        exit();
    }



}

function set_quotes($pdo, $ref_id, $sid)
{
    $dir = $_SERVER['DOCUMENT_ROOT'];
    include_once $dir . "/includes/simple_html_dom.php";
    include_once $dir . "/includes/dbconnect.php";
    $link = 'http://elibrary.ru/author_profile_new_ref_syears.asp?id=' . $ref_id;
    $fp = curl_start($link);
    $html = str_get_html($fp);
    $arrq = array();
    $fo = $html->find('table[id=restab] tr[valign=top] td[class=midtext]');

    for ($i = 0; $i < count($fo); $i += 2) {
        $arrq[trim($fo[$i]->plaintext, '&nbsp;')] = trim($fo[$i + 1]->plaintext, '&nbsp;');
    }

    try{
        $sql = 'INSERT INTO quotes_art SET 
                service = "elib",
                sid = :sid,
                yearsquotes = :yearsq,
                countquotes = :countq;';
        foreach($arrq as $key => $item){
            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':yearsq', $key);
            $s->bindValue(':countq', $item);
            $s->execute();
        }


    }catch (PDOException $e) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/error_db.php";
        set_error("Ошибка бд при обработке setQuotesUser", $e);
        exit();
    }


}

function mainElibUser($elibId, $uq_id){

    $dir = $_SERVER['DOCUMENT_ROOT'];
    include_once $dir . "/includes/simple_html_dom.php";
    include_once $dir . "/includes/dbconnect.php";
    include_once $dir . "/includes/main_data.php";
    $pdo = db_connect();

    $link = 'http://elibrary.ru/author_profile.asp?authorid=' . $elibId;
    $fp = curl_start($link);
    $html = str_get_html($fp);
    $table = $html->find('table[width=580]', 2);
    $el = $table->find('td font');
    $aboutorg = array();
    for ($i = 6; $i < 29; $i += 2) {
        $aboutorg[trim($el[$i]->plaintext, '&nbsp;')] = trim($el[$i + 1]->plaintext, '&nbsp;');
    }
    $hindex = $aboutorg['Индекс Хирша'];
    $un = $html->find('FORM[name=results]',0)->find('table tbody',0)->find('tr',2)->find('td',0)->find('table',0)->find('font b',0);
    error_log("Elidid = " . $hindex . " uqid = " . $uq_id, 0);
    $sid = set_elibMainData($pdo,$un,$elibId,$hindex,$uq_id);
    set_years($pdo, $elibId, $sid);
    set_quotes($pdo, $elibId, $sid);
}
if(isset($_POST['setElibUser'])){
    $elibId = $_POST['ref_id'];
    $uq_id = $_POST['uq_id'];
    ob_end_clean();
    ignore_user_abort();
    ob_start();
    header("Connection: close");
    header("Content-Length: " . ob_get_length());
    ob_end_flush();
    flush();
    sleep(5);
    mainElibUser($elibId, $uq_id);
}