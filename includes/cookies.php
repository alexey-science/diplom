<?php
/**
 * Created by PhpStorm.
 * User: Aleksei Koltashev
 * Date: 27.04.2016
 * Time: 14:04
 */
/**
 * @param integer $parseruid
 * @param string $parsertoken
 * @param string $parserusername
 */
function set_cookies($parseruid, $parsertoken, $parserusername){
    setcookie('parseruserid', $parseruid);
    setcookie('parsertoken', $parsertoken);
    setcookie('parserusername', $parserusername);
}

function isValid_cookie($pdo, $parseruid, $parsertoken){
    try{
        $sql = 'SELECT count(*) AS col FROM tokens WHERE userid = :userid and token = :token';
        $s = $pdo->prepare($sql);
        $s->bindValue(':userid', $parseruid);
        $s->bindValue(':token', $parsertoken);
        $s->execute();
    }catch (PDOException $e){
        include 'error_db.php';
        set_error('Ошибка БД при провекри куков на валидность', $e);
        exit();
    }

    $valid = $s->fetch();
    if($valid['col'] == 1){
        return true;
    }else{
        return false;
    }

}

/**
 * @param PDO $pdo
 * @param integer $userid
 * @return string
 */
function set_tokenDB($pdo, $userid){
    $token = $token = 'U' . $userid . rand(1000000, 10000000);
    try{

        $sql = 'INSERT INTO tokens SET
                token = :token, 
                userid = :id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $userid);

        $s->bindValue(':token', $token);
        $s->execute();
    }catch(PDOException $e){
        include 'error_db.php';
        set_error('Ошибка добавления токена юзеру', $e);
        exit();
    }

    return $token;
}

function delete_tokenDB($pdo, $token, $userid){
    try{

        $sql = 'DELETE FROM tokens WHERE
                userid = :id and token = :token';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $userid);
       $s->bindValue(':token', $token);
        $s->execute();
    }catch(PDOException $e){
        include 'error_db.php';
        set_error('Ошибка удаления токена у юзера', $e);
        exit();
    }

}