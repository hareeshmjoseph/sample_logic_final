<?php

require '/var/www/html/vendor/mikecao/flight/flight/Flight.php';
require '/var/www/html/vendor/thingengineer/mysqli-database-class/MysqliDb.php';


Flight::route('POST /test', function(){
    $db = new MysqliDb ('192.168.150.130', 'trackxDB_dbuser', 'EtCeMCPd8@Ugu3!6', 'trackxDB');
    
    $obj = json_decode(file_get_contents("php://input"));
    if(isset($obj))
    {
        $gmac =$obj[0]->mac;
        for($x=1;$x<sizeof($obj);$x++)
        {
            $timestamp = $obj[$x]->timestamp;
            $rssi = $obj[$x]->rssi;
            $bmac = $obj[$x]->mac;
            $data = Array ("timestamp" => $timestamp,
            "gmac" => $gmac,
            "rssi" => $rssi,
            "bmac" => $bmac
            );
            $id = $db->insert ('test', $data);
            if($id)
                echo 'u=' . $id;
        }

        $cols = Array ("rssi");
        $db->where ('gmac', 'AC233FC02137');
        $db->orderBy("id","Desc");
        $x = $db->get ("test", 1, $cols); 
        //print_r($x);
        $b = $x[0]['rssi'];
        echo $b;

        $cols = Array ("rssi");
        $db->where ('gmac', 'AC233FC016D8');
        $db->orderBy("id","Desc");
        $y = $db->get ("test", 1, $cols); 
        //print_r($y);
        $c = $y[0]['rssi'];
        echo $c;   
        
        if($b > $c)
        {
            echo "<h1>user is near gateway AC233FC02137</h1>";
            $cols = Array ("gmac");
            $y = $db->get ("test1", null, $cols);
            echo "hai" . $db->count;
            if($db->count == 0)
            {                
                $data = Array ("timestamp" => $timestamp,
                "gmac" => $gmac,
                "rssi" => $rssi,
                "bmac" => $bmac
                );
                $id = $db->insert ('test1', $data);
                if($id)
                    echo 'user was created. Id=' . $id;
                
            }elseif($db->count > 0)
            {
                foreach($y as $ab)
                {                        
                    
                }
                echo $ab['gmac'];
                if($ab['gmac'] != 'AC233FC02137' )
                {
                    $data0 = Array ("timestamp" => $timestamp,
                        "gmac" => $gmac,
                        "rssi" => $rssi,
                        "bmac" => $bmac
                    );
                    $id0 = $db->insert ('test1', $data0);
                    if($id0){
                         echo 'user was created. Id=' . $id0;
                    }            
                }
            }
        }elseif($b < $c){
            echo "<h1>user is near gateway AC233FC016D8</h1>";
            
            $cols1 = Array ("gmac");
            $y1 = $db->get ("test1", null, $cols1);
            echo "hai" . $db->count;
            if($db->count == 0)
            {                
                $data1 = Array ("timestamp" => $timestamp,
                "gmac" => $gmac,
                "rssi" => $rssi,
                "bmac" => $bmac
                );
                $id1 = $db->insert ('test1', $data1);
                if($id1)
                    echo 'user was created. Id=' . $id1;
                
            }elseif($db->count > 0)
            {
                foreach($y1 as $ab1)
                {                        
                    
                }
                echo $ab1['gmac'];
                if($ab1['gmac'] != 'AC233FC016D8' )
                {
                    $data2 = Array ("timestamp" => $timestamp,
                        "gmac" => $gmac,
                        "rssi" => $rssi,
                        "bmac" => $bmac
                    );
                    $id2 = $db->insert ('test1', $data2);
                    if($id2){
                         echo 'user was created. Id=' . $id2;
                    }            
                }
            }
        }else{
            echo "<h1>user is in middle</h1>";
        }
    }
        
   
});


Flight::route('/index.php', function(){

    $page = $_SERVER['PHP_SELF'];
    $sec = "1";
    $db1 = new MysqliDb ('192.168.150.130', 'trackxDB_dbuser', 'EtCeMCPd8@Ugu3!6', 'trackxDB');
            $cols = Array ("rssi");
            $db1->where ('gmac', 'AC233FC02137');
            $db1->orderBy("id","Desc");
            $x = $db1->get ("test", 1, $cols); 
            //print_r($x);
            $b = $x[0]['rssi'];
            //echo $b;

            $cols = Array ("rssi");
            $db1->where ('gmac', 'AC233FC016D8');
            $db1->orderBy("id","Desc");
            $y = $db1->get ("test", 1, $cols); 
            //print_r($y);
            $c = $y[0]['rssi'];
            //echo $c;
            
            if($b > $c){
                echo "<h1>User is near gateway AC233FC02137</h1>";
            }elseif($b < $c){
                echo "<h1>User is near gateway AC233FC016D8</h1>";
            }else{
                echo "<h1>user is in middle</h1>";
            }?>
           <html>
    <head>
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    </head>
    <body>
    
    <?php
        
        $db1->join("test1 t1", "t2.id = t1.id + 1", "RIGHT");
        $db1->joinWhere("test1 t1", "t1.bmac", 'AC233F268051');
        $products = $db1->get ("test1 t2", null, "t1.gmac, t1.timestamp, t2.timestamp AS time");
        //print_r ($products);
        for($x=0;$x<sizeof($products)-1;$x++)
        {
                echo " <p>";
                echo "user was in the range of gateway with mac id " . $products[$x]['gmac'] . " from " . $products[$x]['timestamp'] . " to " . $products[$x]['time'];  echo "<p>";
                echo "<br";
            
        } ?> 
    </body>
</html>
   
<?php
    // This will get called
});
Flight::start();
?>

