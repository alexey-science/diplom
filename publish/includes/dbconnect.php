<?php
/**
 * Created by PhpStorm.
 * User: Aleksei Koltashev
 * Date: 30.04.2016
 * Time: 15:54
 */
function db_connect(){
    define("SERVER", 'us-cdbr-azure-west-c.cloudapp.net');
    define("USER", "b531893244973d");
    define("PASSWORD", '75911370');
    try{
        $pdo = new PDO ('mysql:host=' . SERVER . ':3306; dbname=academparser', USER, PASSWORD);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('SET NAMES "utf8"');
        return $pdo;
    }catch (PDOException $e){
        include 'error_db.php';
        set_error('Невозможно подключиться к БД! ', $e);
        exit();
    }
}

//Database=academparser;Data Source=us-cdbr-azure-west-c.cloudapp.net;User Id=b531893244973d;Password=75911370