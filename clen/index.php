<?php

function authorsArtToDB($pdo, $jsonarr, $sid)
{   $authorsart = array();
    foreach ($jsonarr as $item) {
        $json = json_decode($item, true);
        foreach ($json['articles']['items'] as $i) {
            $authors = explode(', ', $i['authors']);
            foreach ($authors as $a) {
                if ($a) {
                    $author = $a;
                } else {
                    $author = 'Без автора';
                }
                if (isset($authorsart[$author])) {
                    $authorsart[$author] += 1;

                } else {
                    $authorsart[$author] = 1;
                }
            }

        }
    }

    try{
        $sql = 'INSERT INTO author_art SET 
                service = "clen",
                sid = :sid,
                author = :author,
                countart = :countart';
        foreach ($authorsart as $key => $item) {
            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':author', $key);
            $s->bindValue(':countart', $item);
            $s->execute();
        }

    }catch (PDOException $e){
        include_once  $_SERVER['DOCUMENT_ROOT'] . '/includes/error_db.php';
        set_error("Ошабка бд при работае с author", $e);
        exit();
    }



}

/**
 * @param PDO $pdo
 * @param string $nojson
 * @param int $sid
 */
function other_infoClen($pdo, $nojson, $sid){
    $json = json_decode($nojson, true);

    try{
        $sql = 'INSERT INTO other_info SET 
                service = "clen",
                sid = :sid,
                name_index = :nindex,
                value_index = :vindex';
        foreach ($json['articles']['aggs']['catalogs'] as $item) {
            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':nindex', 'Число статей в ' . $item['name']);
            $s->bindValue(':vindex', $item['count']);
            $s->execute();

        }
    }catch (PDOException $e){
        include_once  $_SERVER['DOCUMENT_ROOT'] . '/includes/error_db.php';
        set_error("Ошабка бд при работае с oInfo", $e);
        exit();
    }


}

/**
 * @param PDO $pdo
 * @param array $jsonarr
 * @param int $sid
 */
function yearsArtToDB($pdo, $jsonarr, $sid){
    $yearsart = array();

    foreach ($jsonarr as $item) {
        $json = json_decode($item, true);
        foreach ($json['articles']['items'] as $i) {
            $year = $i['year'];
            if (isset($yearsart[$year])) {
                $yearsart[$year] += 1;
            } else {
                $yearsart[$year] = 1;
            }
        }
    }

    try{
        $sql = 'INSERT INTO years_art SET 
                service = "clen",
                sid = :sid,
                years = :years,
                countart = :countart';
        foreach ($yearsart as $key => $item) {
            $s = $pdo->prepare($sql);
            $s->bindValue(':sid', $sid);
            $s->bindValue(':years', $key);
            $s->bindValue(':countart', $item);
            $s->execute();
        }

    }catch (PDOException $e){
        include_once  $_SERVER['DOCUMENT_ROOT'] . '/includes/error_db.php';
        set_error("Ошабка бд при работае с years", $e);
        exit();
    }

}

function parseClenOrg()
{
    $rootDir = $_SERVER['DOCUMENT_ROOT'];
    include_once($rootDir . '/includes/dbconnect.php');
    include_once($rootDir . '/includes/main_data.php');
    $pdo = db_connect();
    $page = 1;
    $jsonarr = array();
    $query = trim($_POST['qtext'], '"');
    $uq_id = $_POST['uq_id'];
    $link = 'http://cyberleninka.ru/search#';


    do {
        $curl = curl_init($link);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2687.0 Safari/537.36 OPR/38.0.2205.0 (Edition developer)');
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'query="'. $query .'"&terms=&catalogs=&page=' . $page);
        $out = curl_exec($curl);
        curl_close($curl);

        $jsonarr[] = $out;
        $page += 1;
        $it = json_decode($out, true);
    } while ((count($it['articles']['items']) > 0) and ($page <= 403));


    $sid = set_clenMainData($pdo, $query, $link . 'query="'. $query .'"', '0','0','0',$uq_id );
    yearsArtToDB($pdo, $jsonarr, $sid);
    authorsArtToDB($pdo, $jsonarr, $sid);
    other_infoClen($pdo, $jsonarr[0], $sid);
}

IF (isset($_POST['setClen'])){
    ob_end_clean();
    ignore_user_abort();
    ob_start();
    header("Connection: close");
    header("Content-Length: " . ob_get_length());
    ob_end_flush();
    flush();
    sleep(5);
    parseClenOrg();
}