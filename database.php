<?php

$conn = mysql_connect($config['db']['host'], $config['db']['user'], $config['db']['password']);
if(!$conn) {
    die('Unable to connect to database: ' .  mysql_error());
}

$db_conn = mysql_select_db($config['db']['name'], $conn);
if(!$db_conn) {
    die('Unable to select database: ' . mysql_error());
}