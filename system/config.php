<?php
session_start();
ob_start();
date_default_timezone_set('Europe/Istanbul');

$config = require __DIR__ . '/config.local.php';
$site_url = $config['site_url'];

try {
    $db = new PDO("mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8", $config['username'], $config['password']);
    $db->query("SET CHARACTER SET utf8");
    $db->query("SET NAMES utf8");
} catch (PDOException $e) {
    print_r($e->getMessage());
}


function checkUserLogin($redirect = true)
{
    global $db, $site_url;

    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
        $loginControl = $db->prepare("SELECT * FROM user_auth WHERE User_Id = :usrid AND Username = :usrname");
        $loginControl->execute([':usrid' => $_SESSION['id'], 'usrname' => $_SESSION['username']]);

        if ($loginControl->rowCount()) {
            return true;
        } else {
            @session_destroy();
            if ($redirect) {
                header('Location: ' . $site_url . 'pages/loginPage.php');
                exit();
            }
        }
    } else {
        @session_destroy();
        if ($redirect) {
            header('Location: ' . $site_url . 'pages/loginPage.php');
            exit();
        }
    }
    return false;
}
