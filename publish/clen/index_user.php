<?php
/**
 * Created by PhpStorm.
 * User: Алексей Колташев
 * Date: 24.04.2016
 * Time: 16:16
**/

function addClenUser($link, $uq_id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    include_once $dir  . '/includes/simple_html_dom.php';
    include_once $dir . '/includes/dbconnect.php';
    include_once  $dir . '/includes/main_data.php';
    $pdo = db_connect();
    $html = new simple_html_dom();
    $page = 1;
    $ya = array();


    do{

        $html->load_file($link . '?page=' . $page);
        $div = $html->find('div[class=content-general] div[class=general-block] div[class=general-block content-general02]');
        foreach ($div as $d) {
            $str = $d->find('div[class=block-inner] div[class=text-inner] span',0)->plaintext;
            $py = explode('/', $str);
            $y = $py[0];
            $ya[$y] = isset($ya[$y])? $ya[$y] + 1 : 1;

        }

        $page++;
    } while(count($div) > 0);
    //Получаем имя Ученого
    $unarr = $html->find('div[id=content] div[class=content-row] h1',0)->plaintext;
    $unarr = trim($unarr);
    $unarr = explode(' ', $unarr);
    $username = $unarr[0] . ' ' . $unarr[1] . ' ' . $unarr[2];

    //Получаем количетсво скачиваний, просмотров, избранное
    $statsarr = $html->find('div[class=stats-block] ul li');
    $download = $statsarr[0]->find('div[class=stats-num]',0)->plaintext;
    $view = $statsarr[1]->find('div[class=stats-num]',0)->plaintext;
    $fav = $statsarr[2]->find('div[class=stats-num]',0)->plaintext;

    $sid = set_clenMainData($pdo,$username,$link,$download,$view,$fav,$uq_id);


        try{
            $sql = 'INSERT INTO years_art SET
             service = "clen",
             sid = :sid,
             years = :years,
             countart = :countart;';
            foreach ($ya as $key => $value) {
            $s = $pdo->prepare($sql);
            $s->bindValue(':years', $key);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':countart', $value);
            $s->execute();
            }

        }catch(PDOException $e){
            include_once $dir . '/includes/error_db.php';
            set_error('Ошибка бд в addClenUser', $e);
            exit();
        }

}

if(isset($_POST['setClenUser'])){

    $link = $_POST['clenref'];
    $uq_id = $_POST['uq_id'];
    //ob_end_clean();
    ignore_user_abort();
    ob_start();
    header("Connection: close");
    header("Content-Length: " . ob_get_length());
    ob_end_flush();
    flush();
    sleep(5);
    set_time_limit(0);
    $link = trim($link, ' \"\'\r\n\t\0\x0B');
    $link = explode('?',$link);
    $link = $link[0];
    addClenUser($link, $uq_id);
}