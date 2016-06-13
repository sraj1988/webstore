<?php

require_once 'config.php';
$conn = mysql_connect($config['db']['host'], $config['db']['user'], $config['db']['password']);
if (!$conn) {
    die('Unable to connect to database: ' . mysql_error());
}

$db_selected = mysql_select_db($config['db']['name'], $conn);

$queries = array();
if (!$db_selected) {
    $sql = "CREATE DATABASE " . $config['db']['name'];
    if (!mysql_query($sql)) {
        echo 'Error creating database: ' . mysql_error() . "\n";
        exit;
    }
    mysql_select_db($config['db']['name'], $conn);


    $queries[] = "CREATE TABLE `products` (
                `product_id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text,
                `SKU` varchar(255) NOT NULL,
                `price` decimal(12,2) NOT NULL DEFAULT '0.00',
                PRIMARY KEY (`product_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

    $queries[] = "CREATE TABLE `users` (
                `uid` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL,
                `password` varchar(255) NOT NULL,
                PRIMARY KEY (`uid`),
                KEY `email` (`email`)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

    $queries[] = "INSERT INTO users SET name='sonu', email=sonu@gmail.com, password='" . md5('123456') . "'";

    for ($i = 1; $i <= 10; $i++) {
        $rand = rand(0, 5000);
        $price = rand(0, 1000);
        $queries[] = "INSERT INTO products SET name='prod-" . $rand . "',  
             description='test product description', sku='sku-prod-" . $rand . "',
             price=$price";
    }
    foreach ($queries as $query) {
        mysql_query($query);
    }
    echo 'Installed';
} else {
    echo 'Already Installed';
}
