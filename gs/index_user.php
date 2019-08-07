<?php
/**
 * Created by PhpStorm.
 * User: Aleksei Koltashev
 * Date: 09.05.2016
 * Time: 15:19
 */

function set_gs($link, $uq_id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    include_once($dir . '/includes/dbconnect.php');
    include_once($dir . '/includes/simple_html_dom.php');
    include_once($dir . '/includes/main_data.php');
    $html = new simple_html_dom();
    $pagesize = 100;
    $cstart = 0;
    $years = array();
    $htmlarr = array();
    $count = 0;
    $userName= '';
    $yearquotes = array();
    do{
        $url = $link . '&cstart=' . $cstart . '&pagesize=' . $pagesize;
        $html->load_file($url);
        $pr = new simple_html_dom();
        $pr->load($html);
        $htmlarr[] = $pr;
        $carr = $html->find('span[id=gsc_a_nn]',0)->plaintext;
        $ca = explode('&ndash;',$carr);
        $count = $ca[1];
        $cstart += $pagesize;

    }while(!($count < $cstart));

    $userName = $html->find('div[id=gsc_prf_in]',0)->plaintext;
    $hindex = $html->find('table[id=gsc_rsb_st] tr', 2)->find('td.gsc_rsb_std',0)->plaintext;
    $pdo = db_connect();

    $sid = set_gsMainData($pdo, $link, $userName, $hindex, $uq_id);
    foreach($htmlarr as $page) {
        $artArr = $page->find('tr.gsc_a_tr span.gsc_a_h');
        foreach ($artArr as $item) {
            $yearText =  $item->plaintext;
            if(strlen($yearText)>1){
                $years[$yearText] +=1;
            }
        }
    }


    try{
        $sql = 'INSERT INTO years_art SET
                    service = "gs",
                    sid = :sid,
                    years = :years,
                    countart = :countart';
        foreach ($years as $key => $year){
            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':years', $key);
            $s->bindValue(':countart', $year);

            $s->execute();
        }
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд в gs ya', $e);
        exit();


    }


    //Получение информации о цитированиии
    $pryq = $html->find('div[id=gsc_g_x] span.gsc_g_t');
    $i = 0;


    try{
        $sql = 'INSERT INTO quotes_art SET
                    service = "gs",
                    sid = :sid,
                    yearsquotes = :yearsq,
                    countquotes = :countq';
        foreach($pryq as $y){
            $yr = $y->plaintext;
            $cq= $html->find('div[id=gsc_g_bars] span.gsc_g_al',$i)->plaintext;
            $i++;

            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':yearsq', $yr);
            $s->bindValue(':countq', $cq);

            $s->execute();
        }
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд в gs qa', $e);
        exit();
    }


}

if(isset($_POST['setgsUser'])){
    $link = $_POST['gsref'];
    $uq_id = $_POST['uq_id'];
    // ob_end_clean();
    ignore_user_abort();
    ob_start();
    header("Connection: close");
    header("Content-Length: " . ob_get_length());
    ob_end_flush();
    flush();
    sleep(5);
    set_time_limit(0);

    $link = trim($link, ' \"\'\r\n\t\0\x0B');
    set_gs($link, $uq_id);
}