<?php

$memcache = new Memcache();
 $memcache->connect("localhost",11211); # You might need to set "localhost" to "127.0.0.1"
 echo "Server's version: " . $memcache->getVersion() . "<br />\n";
 $tmp_object = new stdClass;

 for ($i=0; $i < 1000 ; $i++) { 
    $tmp_object->str_attr = "key".$i;
    $tmp_object->int_attr = "value". $i;
    $memcache->set($tmp_object->str_attr,$tmp_object->int_attr,false,100);
 }

 echo "Store data in the cache (data will expire in 100 seconds)<br />\n";
 echo "Data from the cache:<br />\n";
 for ($i=0; $i < 100; $i++) { 
    // var_dump($memcache->get($tmp_object->str_attr))."\n";
    // echo "\n";
    var_dump([$i])."\n";

 }

?>