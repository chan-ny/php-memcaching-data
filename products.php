<?php

header("Content-Type:application/json");

try {

    $db_name     = 'vrms_db';
    $db_user     = 'root';
    $db_password = '';
    $db_host     = 'localhost';

    $memcache = new Memcache();

    $memcache->addServer("127.0.0.1", 11211);

    $sql = 'SELECT * FROM `vehicle_info` order by created_at DESC  LIMIT 1000 offset 0';
    
    $key = md5($sql);
    
    $object =  array("id"=> 101319, "owner_name"=>"testing abc");
    
    $cached_data = $memcache->get($object);

    $response = [];

    if ($cached_data != null) {

        $response['Memcache Data'] = $cached_data;

    } else {

        $pdo = new PDO("mysql:host=" . $db_host  . ";dbname=" . $db_name, $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $products = [];

        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $memcache->set($row[id], $row, false, 100); // second
            $products[] = $row;
        }

        // $memcache->set($key, $products, false, 100); // second

        $response['MySQL Data'] =  $products;

    }

    echo json_encode($response, JSON_PRETTY_PRINT) . "\n";

} catch(PDOException $e) {
    $error = [];
    $error['message'] = $e->getMessage();
    echo json_encode($error, JSON_PRETTY_PRINT) . "\n";
}