<?php
/**
 * Created by PhpStorm.
 * User: Aleksei Koltashev
 * Date: 06.05.2016
 * Time: 21:14
 */

/**
 * @param  PDO $pdo
 * @param string $name
 * @param string $ref
 * @param int $downloads
 * @param int $views
 * @param int $fav
 * @param int $uq_id
 * @return  int
 */
function set_clenMainData($pdo, $name, $ref, $downloads, $views, $fav, $uq_id){
    $dir  = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = "INSERT INTO clen SET 
              name = :name,
              ref  = :ref,
              downloads = :downloads,
              views = :views,
              fav = :fav,
              dateq = NOW(),
              uq_id = :uq_id;";
        $s = $pdo->prepare($sql);
        $s->bindValue(":name", $name);
        $s->bindValue(":ref", $ref);
        $s->bindValue(":downloads", $downloads);
        $s->bindValue(":views", $views);
        $s->bindValue(":fav", $fav);
        $s->bindValue(":uq_id", $uq_id);
        $s->execute();

    }catch (PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении clenMainData', $e);
        exit();
    }

    return $pdo->lastInsertId();
}

/**
 * @param  PDO $pdo
 * @param string $ref
 * @param string $name
 * @param int $hindex
 * @param int $uq_id
 * @return int
 */
function set_gsMainData($pdo, $ref, $name, $hindex, $uq_id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'INSERT INTO gs SET
                    ref = :ref,
                    name = :name,
                    hindex = :hindex,
                    uq_id = :uq_id,
                    dateq = NOW();';
        $s = $pdo->prepare($sql);
        $s->bindValue(':ref', $ref);
        $s->bindValue(':name', $name);
        $s->bindValue(':hindex', $hindex);
        $s->bindValue(':uq_id', $uq_id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд в mainGS', $e);
        exit();
    }

    return $pdo->lastInsertId();
}




function set_elibMainData($pdo, $name, $refid, $hindex, $uq_id){
    $dir  = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = "INSERT INTO elib SET 
              name = :name,
              refid  = :refid,
              hindex = :hindex,
              dateq = NOW(),
              uq_id = :uq_id;";
        $s = $pdo->prepare($sql);
        $s->bindValue(":name", $name);
        $s->bindValue(":refid", $refid);
        $s->bindValue(":hindex", $hindex);
        $s->bindValue(":uq_id", $uq_id);
        $s->execute();

    }catch (PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении elibMainData', $e);
        exit();
    }

    return $pdo->lastInsertId();
}

/**
 * @param PDO $pdo
 * @param int $uq_id
 * @return mixed
 */
function getUserQueryById($pdo, $uq_id){
    $dir  = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM users_query WHERE
                id = :uq_id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':uq_id', $uq_id);
        $s->execute();
    }catch (PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getUQByID', $e);
        exit();
    }

    return $s->fetch();
}



/**
 * @param PDO $pdo
 * @param int $user_id
 * @param int $prior
 * @return array();
 */
function get_UserQuery($pdo, $user_id, $prior){
    $dir  = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM users_query WHERE
                priorq = :priorq and user_id = :user_id';
        $s = $pdo->prepare($sql);
        $s->bindValue(':priorq', $prior);
        $s->bindValue(':user_id', $user_id);
        $s->execute();
    }catch (PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getUQ', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param array $sumids
 * @param array $delids
 * @param string $targetid
 *
 */
function grouping_info($pdo, $sumids, $delids, $targetid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    $instr = 'in(';
    foreach ($sumids as $id){
        $instr .= $id . ',';
    }
    $instr .= '-1)';

    try{
        $sql = 'UPDATE author_art SET countart = (
SELECT SUM(t.countart) FROM (SELECT * FROM author_art) as t WHERE t.id ' . $instr .') WHERE id = ' . $targetid;
        $s = $pdo->prepare($sql);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении groupinfo', $e);
        exit();
    }
    error_log("Sql " . $sql,0);

    $instr = 'in(';
    foreach ($delids as $id){
        $instr .= $id . ',';
    }
    $instr .= '-1)';
    try{
        $sql = 'DELETE FROM  author_art WHERE id ' . $instr;
        $s = $pdo->prepare($sql);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд удаления при выполнении groupinfo', $e);
        exit();
    }
}

/**
 * @param PDO $pdo
 * @param  int $uq_id
 * @return array
 */
function get_Clen($pdo, $uq_id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM clen WHERE uq_id=:uq_id ORDER BY dateq DESC;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':uq_id', $uq_id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getClen', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param  int $uq_id
 * @return array
 */
function get_Elib($pdo, $uq_id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM elib WHERE uq_id=:uq_id  ORDER BY dateq DESC;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':uq_id', $uq_id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getElib', $e);
        exit();
    }

    return $s->fetchAll();
}/**
 * @param PDO $pdo
 * @param  int $uq_id
 * @return array
 */
function get_gs($pdo, $uq_id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM gs WHERE uq_id=:uq_id ORDER BY dateq DESC;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':uq_id', $uq_id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getGs', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param  int $id
 * @return array
 */
function get_ClenById($pdo, $id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM clen WHERE id=:id;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getClenById', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param  int $id
 * @return array
 */
function get_ElibById($pdo, $id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM elib WHERE id=:id;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getElibById', $e);
        exit();
    }

    return $s->fetchAll();
}/**
 * @param PDO $pdo
 * @param  int $id
 * @return array
 */
function get_gsById($pdo, $id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM gs WHERE id=:id;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':id', $id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getGsById', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param string $service
 * @param int $sid
 * @return mixed
 */
function getQuotes($pdo, $service, $sid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM quotes_art WHERE service= :service and sid = :sid ORDER BY yearsquotes DESC ';
        $s = $pdo->prepare($sql);
        $s->bindValue(':service', $service);
        $s->bindValue(':sid', $sid);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getQ', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param string $service
 * @param int $sid
 * @return mixed
 */
function getAuthor($pdo, $service, $sid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM author_art WHERE service= :service and sid = :sid ORDER BY countart DESC;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':service', $service);
        $s->bindValue(':sid', $sid);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getA', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param string $service
 * @param int $sid
 * @return mixed
 */
function getYears($pdo, $service, $sid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM years_art WHERE service= :service and sid = :sid ORDER BY years DESC;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':service', $service);
        $s->bindValue(':sid', $sid);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getY', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param string $service
 * @param int $sid
 * @return mixed
 */
function getOtherInfo($pdo, $service, $sid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'SELECT * FROM other_info WHERE service= :service and sid = :sid;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':service', $service);
        $s->bindValue(':sid', $sid);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении getOI', $e);
        exit();
    }

    return $s->fetchAll();
}

/**
 * @param PDO $pdo
 * @param int $id_q
 */

 function del_Query($pdo, $id_q){
     $dir = $_SERVER['DOCUMENT_ROOT'];
     try{
         $sql = 'DELETE FROM users_query WHERE id= :id;';
         $s = $pdo->prepare($sql);
         $s->bindValue(':id', $id_q);
         $s->execute();
     }catch(PDOException $e){
         include $dir . '/includes/error_db.php';
         set_error('Ошибка бд при выполнении delQ', $e);
         exit();
     }
}


/**
 * @param PDO $pdo
 * @param string $service
 * @param int $sid
 */
function deleteServiceDataById($pdo, $service, $sid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'DELETE FROM ' . $service . ' WHERE id  = :sid';
        $s = $pdo->prepare($sql);
        $s->bindValue(':sid', $sid);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении delServDataById', $e);
        exit();
    }
}
/**
 * @param PDO $pdo
 * @param string $service
 * @param int $uq_id
 */
function del_serviceData($pdo, $service, $uq_id){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'DELETE FROM ' . $service . ' WHERE uq_id= :uq_id;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':uq_id', $uq_id);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении delServData', $e);
        exit();
    }
}

/**
 * @param PDO $pdo
 * @param  string $info
 * @param string $service
 * @param int $sid
 */
function del_OtherInfo($pdo, $info, $service, $sid){
    $dir = $_SERVER['DOCUMENT_ROOT'];
    try{
        $sql = 'DELETE FROM ' . $info . ' WHERE service= :service and sid = :sid;';
        $s = $pdo->prepare($sql);
        $s->bindValue(':service', $service);
        $s->bindValue(':sid', (int) $sid);
        $s->execute();
    }catch(PDOException $e){
        include $dir . '/includes/error_db.php';
        set_error('Ошибка бд при выполнении delOI', $e);
        exit();
    }
}



