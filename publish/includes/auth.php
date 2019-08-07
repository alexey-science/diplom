<?php
/**
 * Created by PhpStorm.
 * User: Alekse Koltashev
 * Date: 27.04.2016
 * Time: 21:12
 */
/**
 * @param PDO $pdo
 * @param string $username
 * @param  string $email
 * @param  string $pass
 * @param  string $typeacc
 * @return integer
 */
function registry_user($pdo, $username, $email, $pass){
    try{
        $sql = 'INSERT INTO users SET
                email = :email,
                name = :name,
                pass = :pass;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $email);
        $s->bindValue(':name', $username);
        $s->bindValue(':pass', $pass);

        $s->execute();
    }catch(PDOException $e){
        include 'error_db.php';
        set_error('Ошибка добавления новго пользователя ', $e);
        exit();
    }

    return $pdo->lastInsertId();
}


/**
 * @param PDO $pdo
 * @param string $name
 * @param  string $gs_ref
 * @param string $clen_ref
 * @param string $elib_ref
 * @param int $priorq,
 * @param int $typeq,
 * @param int $user_id
 * @return int
 */
function registry_userquery($pdo,$name,$gs_ref,$clen_ref, $elib_ref,$priorq , $typeq, $user_id){
    try{
        $sql = 'INSERT INTO users_query SET
                name = :name,
                gs_ref = :gs_ref,
                clen_ref = :clen_ref,
                elib_ref = :elib_ref,
                priorq = :priorq,
                typeq = :typeq,
                user_id = :user_id;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':name', $name);
        $s->bindValue(':gs_ref', $gs_ref);
        $s->bindValue(':clen_ref', $clen_ref);
        $s->bindValue(':elib_ref', $elib_ref);
        $s->bindValue(':priorq', $priorq);
        $s->bindValue(':typeq', $typeq);
        $s->bindValue(':user_id', $user_id);
        $s->execute();
    }catch(PDOException $e){
        include 'error_db.php';
        set_error('Ошибка добавления новго пользователя ', $e);
        exit();
    }

    return $pdo->lastInsertId();
}

/**
 * @param PDO $pdo
 * @param  string $email
 * @return boolean
 */
function isValid_email($pdo, $email){
    try{
        $sql = 'SELECT COUNT(*) AS col FROM users WHERE email = :email';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $email);
        $s->execute();
    }catch(PDOException $e){
        include 'error_db.php';
        set_error('Ошибка БД при провекри емайла пользователя ', $e);
        exit();
    }

    $row = $s->fetch();
    if($row['col'] > 0){
        return false;
    }else{
        return true;
    }
}

/**
 * @param PDO $pdo
 * @param string $email
 * @param string $pass
 * @return array
 */
function login($pdo, $email, $pass){
    try{
        $sql = 'SELECT * FROM users WHERE email = :email and pass = :pass;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':email', $email);
        $s->bindValue(':pass', $pass);
        $s->execute();
    }catch(PDOException $e){
        include 'error_db.php';
        set_error('Ошибка БД при проверки пользователя ', $e);
        exit();
    }

    return $s->fetch();
}