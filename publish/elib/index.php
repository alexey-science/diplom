<?php

function curl_start($link)
{
    $ch = curl_init($link);

    $coo = 'cookie/coo.txt';
    $data="";

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
//Закрываем дексриптор файла
    return $filename;
}



/**
 * @param PDO $pdo
 * @param int $sid
 * @param int $refid
 */
function set_author($pdo, $sid, $refid){
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/simple_html_dom.php";
    $link = "http://elibrary.ru/org_profile2_authors.asp?id=" . $refid;
    $filename = curl_start($link);
    $html =  str_get_html($filename);
    $arrauth = array();
    $fo = $html->find('table[id=restab] tr[valign=top] td[class=midtext]');
    for($i=0; $i<count($fo); $i+=3){
        $arrauth[trim($fo[$i+1]->plaintext,'&nbsp;')] = trim($fo[$i+2]->plaintext,'&nbsp;');
    }
    try{
        $sql = 'INSERT INTO author_art SET 
                service = "elib",
                sid = :sid,
                author = :author,
                countart = :countart;';
        foreach ($arrauth as $key => $item){
           $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':author', $key);
            $s->bindValue(':countart', $item);
            $s->execute();
        }


    }catch (PDOException $e) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/error_db.php";
        set_error("Ошибка бд при обработке setAuthor", $e);
        exit();
    }
}

/**
 * @param PDO $pdo
 * @param int $sid
 * @param  int  $refid
 * @param simple_html_dom $html
 */
function other_info($pdo, $sid, $refid, $html){
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/simple_html_dom.php";

    $table = $html->find('table[width=580]', 3);
    $el = $table->find('td font');
    $arrother = array();
    for ($i = 0; $i < count($el); $i += 2) {
        $namei = trim($el[$i]->plaintext, '&nbsp;');
        if($namei === "Число статей в российских журналах из перечня ВАК"
            or $namei === "Число статей в журналах, входящих в RSCI"
            or $namei === "Число статей в журналах, входящих в Web of Science или Scopu"
            or $namei === "Число статей в журналах, входящих в ядро РИНЦ"){
        $arrother[$namei] = trim($el[$i + 1]->plaintext, '&nbsp;');
        }
    }
    $link = "http://elibrary.ru/org_profile2_citednum.asp?id=" . $refid;
    $filehtml = curl_start($link);
    $html =  str_get_html($filehtml);
    $allcited = 0;
    $fo = $html->find('table[id=restab] tr[valign=top] td[class=midtext]');

    for($i=0; $i<count($fo); $i+=2){
        $allcited += (int) trim($fo[$i]->plaintext,'&nbsp;') * (int)trim($fo[$i+1]->plaintext,'&nbsp;');
    }
    try{
        $sql = 'INSERT INTO other_info SET 
                service = "elib",
                sid = :sid,
                name_index = :nindex,
                value_index = :vindex;';
        foreach($arrother as $key => $item){
            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':nindex', $key);
            $s->bindValue(':vindex', $item);
            $s->execute();
        }
        $s = $pdo->prepare($sql);
        $s->bindValue(':sid', $sid);
        $s->bindValue(':nindex', 'Всего цитирований');
        $s->bindValue(':vindex', $allcited);
        $s->execute();
    }catch (PDOException $e) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/error_db.php";
        set_error("Ошибка бд при обработке other-Info", $e);
        exit();
    }
}

function set_years($pdo, $sid, $refid){
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/simple_html_dom.php";

    $link = "http://elibrary.ru/org_profile2_years.asp?id=" . $refid;
    $filename = curl_start($link);
    $html =  str_get_html($filename);
    $arryears = array();
    $fo = $html->find('table[id=restab] tr[valign=top] td[class=midtext]');

    for($i=0; $i<count($fo); $i+=2){
        $arryears[trim($fo[$i]->plaintext,'&nbsp;')] = trim($fo[$i+1]->plaintext,'&nbsp;');
    }

    try{
        $sql = 'INSERT INTO years_art SET 
                service = "elib",
                sid = :sid,
                years = :years,
                countart = :countart;';
        foreach ($arryears as $key => $item){
            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':years', $key);
            $s->bindValue(':countart', $item);
            $s->execute();
        }


    }catch (PDOException $e) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/error_db.php";
        set_error("Ошибка бд при обработке setYears", $e);
        exit();
    }

    }

function mainElib()
{
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/simple_html_dom.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/main_data.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/dbconnect.php";

    $pdo = db_connect();
    $refid = $_POST['ref_id'];
    $uq_id = $_POST['uq_id'];
    $link = "http://elibrary.ru/org_profile.asp?id=" . $refid;
    $filename = curl_start($link);
    $html = str_get_html($filename);
    $table = $html->find('table[width=580]', 2);
    $name = $html->find('table[width=580]', 1)->find('font[color=#F26C4F] b', 0)->plaintext;
    $el = $table->find('td font');
    $aboutorg = array();
    for ($i = 0; $i < count($el); $i += 2) {
        $aboutorg[trim($el[$i]->plaintext, '&nbsp;')] = trim($el[$i + 1]->plaintext, '&nbsp;');
    }

    $sid = set_elibMainData($pdo,$name,$refid,$aboutorg['h-индекс (индекс Хирша)'], $uq_id);
    set_author($pdo, $sid, $refid);
    set_years($pdo, $sid, $refid);
    other_info($pdo, $sid , $refid , $html);

}


if(isset($_POST['setElib'])){
    ob_end_clean();
    ignore_user_abort();
    ob_start();
    header("Connection: close");
    header("Content-Length: " . ob_get_length());
    ob_end_flush();
    flush();
    sleep(5);
    mainElib();
}
//echo date('y.m.d  s');

