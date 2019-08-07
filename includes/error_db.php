<?php
/**
 * Created by PhpStorm.
 * User: Aleksei Koltashev
 * Date: 27.04.2016
 * Time: 14:16
 */
function set_error($text_msg, $info){
    $error = $text_msg . ' ' . $info->getMessage();
    include $_SERVER['DOCUMENT_ROOT'] . '/templates/error.html.php';
    exit();
}