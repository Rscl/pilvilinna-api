<?php
header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: *');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With,X-Token");
        header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        exit(0);
    }
?>
