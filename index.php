<?php
/**
 * Created by PhpStorm.
 * User: Aleksei Koltashev
 * Date: 30.04.2016
 * Time: 14:51
 */
//Константы ид пользователя, токен, имя пользователя берут из куков
define('USERID', $_COOKIE['parseruserid']);
define('TOKEN', $_COOKIE['parsertoken']);
define('UNAME', $_COOKIE['parserusername']);
define('HOSTNAME', 'parserorg.loc');
mb_internal_encoding("UTF-8");
function runAsync($url, $options= array()){
    $fields = $options;

    $curl_options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => http_build_query( $fields ),
        CURLOPT_HTTP_VERSION => 1.0,
        CURLOPT_HEADER => 0,
        CURLOPT_TIMEOUT => 1
    );
    $curl = curl_init();
    curl_setopt_array( $curl, $curl_options );
    curl_exec( $curl );
    curl_close( $curl );

}

function load_temp($includeName, $eventNavBtnOne, $eventNavBtnTwo, $textNavBtnOne, $textNavBtnTwo, $params= array()){
    $rootDir = $_SERVER['DOCUMENT_ROOT'];
    $inc = $includeName;
    $eventNavBtn1 = $eventNavBtnOne;
    $eventNavBtn2 = $eventNavBtnTwo;
    $textNavBtn1 =  $textNavBtnOne;
    $textNavBtn2 = $textNavBtnTwo;
    include $rootDir . '/templates/template.html.php';
    exit();
}



//Главная функиця для загрузки главной страницы
function main(){
    if(isset($_GET['enter'])  and !(USERID) and !UNAME and !TOKEN){
        load_temp('login','?enter','?signup','Вход','Регистрация');
    }
    if(isset($_GET['signup']) and !(USERID) and !UNAME and !TOKEN){
        load_temp('register','?enter','?signup','Вход','Регистрация');
    }
    if(isset($_GET['registry']) and !TOKEN and  !UNAME and !USERID ){
        include_once 'includes/dbconnect.php';
        include_once 'includes/auth.php';
        include_once 'includes/cookies.php';
        $pdo = db_connect();
        $pass = $_POST['pass'];
        $name = $_POST['name'];
        $email = mb_strtolower($_POST['email'], "UTF-8");
        $type = $_POST['acctype'];
        if($email and isValid_email($pdo, $email)){
           $userid =  registry_user($pdo, $name ,$email,$pass);
           $token =  set_tokenDB($pdo,$userid);
            set_cookies($userid,$token,$name);
            $gsref = isset($_POST['gsRef'])?$_POST['gsRef']:'none';
            $clenref = $_POST['clenRef'];
            $elibref = $_POST['elibRef'];

            $id_query = registry_userquery($pdo,$name,$gsref,$clenref, $elibref,  1, $type, $userid);
            if($type == 'org'){
            runAsync(HOSTNAME . '/elib/index.php',array('setElib'=>true,'ref_id' => $elibref, 'uq_id' => $id_query));
            runAsync(HOSTNAME . '/clen/index.php',array('setClen'=>true,'qtext' => $clenref, 'uq_id' => $id_query));
            }
            if($type == 'user'){
                runAsync(HOSTNAME . '/elib/index_user.php',array('setElibUser'=>true,'ref_id' => $elibref, 'uq_id' => $id_query));
                runAsync(HOSTNAME . '/clen/index_user.php',array('setClenUser'=>true,'clenref' => $clenref, 'uq_id' => $id_query));
                runAsync(HOSTNAME . '/gs/index_user.php',array('setgsUser'=>true,'gsref' => $gsref, 'uq_id' => $id_query));
            }

            
        }else{
            load_temp('register','?enter','?signup','Вход','Регистрация',array('mes'=>'Пользователь с таким email уже зарегистрирован'));
        }
        header('Location: . ');
        exit();
    }

    if(isset($_GET['login']) and !TOKEN and  !UNAME and !USERID){
        include_once 'includes/cookies.php';
        include_once 'includes/auth.php';
        include_once 'includes/dbconnect.php';
        $pdo = db_connect();
        $email = mb_strtolower($_POST['email'],"UTF-8");
        $pass = $_POST['pass'];
        $rowus = login($pdo,$email,$pass);
        if($rowus){
        $token = set_tokenDB($pdo,$rowus['id']);
        set_cookies($rowus['id'],$token,$rowus['name']);
        } else {
            load_temp('login','?enter','?signup','Вход','Регистрация',array('mes'=>'Неверный email или пароль'));
        }
        header('Location: . ');
        exit();
    }

    if(isset($_GET['outlogin'])){
        include_once 'includes/cookies.php';
        include_once 'includes/dbconnect.php';
        $pdo = db_connect();
        delete_tokenDB($pdo, TOKEN, USERID);
        set_cookies('','','');
        header('Location: . ');
        exit();
    }

    if(isset($_GET['reg_uq'])){
        include_once 'includes/dbconnect.php';
        include_once 'includes/auth.php';

        $pdo = db_connect();
        $name = $_POST['name'];
        $type = $_POST['acctype'];
        $gsref = isset($_POST['gsRef'])?$_POST['gsRef']:'none';
        $clenref = $_POST['clenRef'];
        $elibref = $_POST['elibRef'];
        $id_query = registry_userquery($pdo,$name,$gsref,$clenref, $elibref,  0, $type, USERID);
        if($type == 'org'){
            runAsync(HOSTNAME . '/elib/index.php',array('setElib'=>true,'ref_id' => $elibref, 'uq_id' => $id_query));
            runAsync(HOSTNAME . '/clen/index.php',array('setClen'=>true,'qtext' => $clenref, 'uq_id' => $id_query));
        }
        if($type == 'user'){
            runAsync(HOSTNAME . '/elib/index_user.php',array('setElibUser'=>true,'ref_id' => $elibref, 'uq_id' => $id_query));
            runAsync(HOSTNAME . '/clen/index_user.php',array('setClenUser'=>true,'clenref' => $clenref, 'uq_id' => $id_query));
            runAsync(HOSTNAME . '/gs/index_user.php',array('setgsUser'=>true,'gsref' => $gsref, 'uq_id' => $id_query));
        }
        sleep(7);
        header('Location: . ');
        exit();
        }

    if(isset($_GET['getInfo']) and USERID and TOKEN){
        include_once 'includes/dbconnect.php';
        include_once 'includes/main_data.php';
        include_once 'includes/cookies.php';
        $pdo = db_connect();
        if(USERID and TOKEN and isValid_cookie($pdo, USERID, TOKEN)){
            $uq_id = $_GET['uq_id'];
            $uqArr = getUserQueryById($pdo, $uq_id);
            $type = $uqArr['typeq'];
            $query = array();
            if($uqArr) {
                $query[] = get_UserQuery($pdo, USERID, 1);
                $query[] = get_UserQuery($pdo, USERID, 0);
                if ($type == 'org') {
                    $serv['elib'] = get_Elib($pdo, $uq_id);
                    $serv['clen'] = get_Clen($pdo, $uq_id);
                }
                if ($type == 'user') {
                    $serv['elib'] = get_Elib($pdo, $uq_id);
                    $serv['clen'] = get_Clen($pdo, $uq_id);
                    $serv['gs'] = get_gs($pdo, $uq_id);
                }
                load_temp('article', '#', '?outlogin', UNAME, 'Выход', array('query' => $query, 'serv' => $serv, 'type' => $type));
            }else{
                header("Location: .");
                exit();
            }
        }else{
            header("Location: .");
            exit();
        }
    }

    if(isset($_GET['update']) and USERID and TOKEN){
        include_once 'includes/dbconnect.php';
        include_once 'includes/main_data.php';
        include_once 'includes/cookies.php';
        $pdo = db_connect();
        if(USERID and TOKEN and isValid_cookie($pdo, USERID, TOKEN)){
            $id_query = $_GET['uq_id'];
            $uqArr = getUserQueryById($pdo, $id_query);
            $type= $uqArr['typeq'];
            $gsref= $uqArr['gs_ref'];
            $elibref = $uqArr['elib_ref'];
            $clenref = $uqArr['clen_ref'];

            if($type == 'org'){
                runAsync(HOSTNAME . '/elib/index.php',array('setElib'=>true,'ref_id' => $elibref, 'uq_id' => $id_query));
                runAsync(HOSTNAME . '/clen/index.php',array('setClen'=>true,'qtext' => $clenref, 'uq_id' => $id_query));
            }
            if($type == 'user'){
                runAsync(HOSTNAME . '/elib/index_user.php',array('setElibUser'=>true,'ref_id' => $elibref, 'uq_id' => $id_query));
                runAsync(HOSTNAME . '/clen/index_user.php',array('setClenUser'=>true,'clenref' => $clenref, 'uq_id' => $id_query));
                runAsync(HOSTNAME . '/gs/index_user.php',array('setgsUser'=>true,'gsref' => $gsref, 'uq_id' => $id_query));
            }
            sleep(7);
            header('Location: . ');
            exit();
        }else{

            header('Location: . ');
            exit();
        }
    }
    
    if(isset($_GET['del'])  and USERID and TOKEN){
        include_once 'includes/dbconnect.php';
        include_once 'includes/main_data.php';
        include_once 'includes/cookies.php';
        $pdo = db_connect();
        if(isValid_cookie($pdo, USERID, TOKEN)){
            $id_query = $_GET['uq_id'];
            $aelib = get_Elib($pdo, $id_query);
            $aclen = get_Clen($pdo, $id_query);
            $ags = get_gs($pdo, $id_query);
            foreach ($aelib as $item){
                $sid = $item['id'] * 1;
                del_OtherInfo($pdo,'author_art' , 'elib' , $sid);
                del_OtherInfo($pdo,'other_info' , 'elib' , $sid);
                del_OtherInfo($pdo,'quotes_art' , 'elib' , $sid);
                del_OtherInfo($pdo,'years_art' , 'elib' , $sid);
            }
            foreach ($aclen as $item){
                $sid = $item['id'] * 1;
                del_OtherInfo($pdo,'author_art' , 'clen' , $sid);
                del_OtherInfo($pdo,'other_info' , 'clen' , $sid);
                del_OtherInfo($pdo,'quotes_art' , 'clen' , $sid);
                del_OtherInfo($pdo,'years_art' , 'clen' , $sid);
            }
            foreach ($ags as $item){
                $sid = $item['id'] * 1;
                del_OtherInfo($pdo,'author_art' , 'gs' , $sid);
                del_OtherInfo($pdo,'other_info' , 'gs' , $sid);
                del_OtherInfo($pdo,'quotes_art' , 'gs' , $sid);
                del_OtherInfo($pdo,'years_art' , 'gs' , $sid);
            }

            del_serviceData($pdo,'gs', $id_query);
            del_serviceData($pdo,'elib', $id_query);
            del_serviceData($pdo,'clen', $id_query);
            del_Query($pdo, $id_query);
        }
        header('Location: . ');
        exit();
        
    }

    if(isset($_GET['delItem'])  and USERID and TOKEN){
        include_once 'includes/dbconnect.php';
        include_once 'includes/main_data.php';
        include_once 'includes/cookies.php';
        $pdo = db_connect();
        if(isValid_cookie($pdo, USERID, TOKEN)){
            $sid = $_GET["sid"];
            $service = $_GET["service"];
            del_OtherInfo($pdo,'author_art' , $service , $sid);
            del_OtherInfo($pdo,'other_info' , $service , $sid);
            del_OtherInfo($pdo,'quotes_art' , $service , $sid);
            del_OtherInfo($pdo,'years_art' , $service , $sid);

            deleteServiceDataById($pdo, strval($service), $sid);

        }
        header('Location: . ');
        exit();

    }

    if(isset($_GET['getStat'])){
        include_once 'includes/dbconnect.php';
        include_once 'includes/main_data.php';
        $pdo = db_connect();
        $service = $_GET['service'];
        $type = $_GET['type'];
        $sid = $_GET['sid'];
        $serv = array();
        $quotes = array();
        $author = array();
        $years = array();

        $quotes = getQuotes($pdo, $service, $sid);
        $years = getYears($pdo, $service, $sid);
        $author = getAuthor($pdo, $service, $sid);
        $otherinfo = getOtherInfo($pdo, $service, $sid);
        switch ($service){
            case 'elib':
                $serv['elib'] = get_ElibById($pdo, $sid);
                load_temp('elibTb','#','?outlogin',UNAME,'Выход', array('type'=>$type, 'serv' => $serv,
                    'years'=>$years, 'authors'=>$author, 'quotes'=>$quotes, 'otherInfo'=> $otherinfo));

                break;
            case 'gs':
                $serv['gs'] = get_gsById($pdo, $sid);
                load_temp('gsTb','#','?outlogin',UNAME,'Выход', array('type'=>$type, 'serv' => $serv, 'years'=>$years, 'quotes'=>$quotes));

                break;
            case 'clen':
                $serv['clen'] = get_ClenById($pdo, $sid);
                load_temp('clenTb','#','?outlogin',UNAME,'Выход', array('type'=>$type,
                    'serv' => $serv, 'years'=>$years,
                    'authors'=>$author, 'otherInfo'=>$otherinfo));
                break;
        }


    }

    if(isset($_POST['grouping'])){
        if(isset($_POST['ids'])){
            include_once 'includes/dbconnect.php';
            include_once 'includes/main_data.php';
            $pdo = db_connect();
            $ids = $_POST["ids"];
            $noids = $ids;
            $targetid = $noids[0];
            unset($noids[0]);
            grouping_info($pdo,$ids,$noids,$targetid);
            header( "Location: " . $_POST['grouping']);
        }
    }

    if(isset($_GET['getCSV'])){
        $dir = $_SERVER['DOCUMENT_ROOT'];
        include_once $dir . '/includes/generics.php';
        $na = iconv('utf-8', 'windows-1251',  $_GET['name']);
        $na =  iconv('windows-1251', 'utf-8',  $na);
        echo iconv('windows-1251', 'utf-8',HTML_to_CSV($_POST['texth'], $na));
        exit();
    }


    If(USERID and TOKEN and UNAME){
        include_once 'includes/cookies.php';
        include_once 'includes/dbconnect.php';
        $pdo = db_connect();
        if(isValid_cookie($pdo,USERID, TOKEN)){
            include_once 'includes/main_data.php';
            $query = array();
            $serv = array();
            $query[] = get_UserQuery($pdo, USERID, 1);
            $query[] = get_UserQuery($pdo, USERID, 0);
            $type = $query[0][0]['typeq'];
            $uq_id = $query[0][0]['id'];
            if($type == 'org'){
                $serv['elib'] = get_Elib($pdo, $uq_id);
                $serv['clen'] = get_Clen($pdo, $uq_id);
            }
            if($type == 'user'){
                $serv['elib'] = get_Elib($pdo, $uq_id);
                $serv['clen'] = get_Clen($pdo, $uq_id);
                $serv['gs'] = get_gs($pdo, $uq_id);
            }
            
            load_temp('article','#','?outlogin',UNAME,'Выход', array('query'=>$query, 'serv' => $serv, 'type'=> $type));
        }else{
            set_cookies('','','');
            header('Location: . ');
            exit();
        }
    }
    load_temp('main','?enter','?signup','Вход','Регистрация');
}

main();