<?php
if (empty($_GET["clan"])) {
    echo 'No clan specified';
} 
else {

$clan_id = $_GET["clan"];


$url = "http://live.cod.gs/incl/json/" . $clan_id . "/roster.json";
$urlcw = "http://live.cod.gs/incl/json/" . $clan_id . "/cw.json";

$clan_db = "clan_" . $clan_id;
$nodes_db = "nodes_" . $clan_id . '';

    
    try {
        
        $host   = "HOST";
        $dbname = "DB_NAME";
        $user   = "USER";
        $pass   = "PASS";
        
        # MySQL with PDO_MYSQL
        $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        
    }
    catch (PDOException $e) {
        echo "ERROR: There was a problem connecting to the database";
    }
    
    //create dbs

    try {
        
        $create_roster = $DBH->prepare("CREATE TABLE clan_8447 (
            id varchar(50) NOT NULL,
   username VARCHAR(20) NOT NULL,
   kills VARCHAR(10) NOT NULL,
   deaths varchar(10) NOT NULL,
   wins VARCHAR(10) NOT NULL,
   losses VARCHAR(10) NOT NULL,
   kdr varchar(15) NOT NULL,
   delta INT(10) NOT NULL,
   kdr_delta float NOT NULL,
   PRIMARY KEY ( id ))");

        

       $create_roster->bindParam(':table', $clan_db);
        
        $create_roster->execute();
        
        echo 'Roster database created successfully<br />';
          }
        
    catch (PDOException $e) {
        echo "ERROR: There was a problem creating the clan database.";
        
    }
       try {
        
        $create_nodes = $DBH->prepare("CREATE TABLE nodes_8447 (
            id varchar(3) NOT NULL,
   game_mode VARCHAR(10) NOT NULL,
   shields INT(5) NOT NULL,
   delta INT(5) NOT NULL)");

        $create_nodes->bindParam(':table', $nodes_db);
        
        $create_nodes->execute();
        
        echo 'Nodes database created successfully<br />';
          }
        
    catch (PDOException $e) {
        echo "ERROR: There was a problem creating the nodes database.";
        
    }

    //initial json get

    $token = 'TOKEN';

     $jsonurl = 'API_URL'; // Generate API call
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_URL, $jsonurl);
     curl_setopt($ch, CURLOPT_ENCODING , "gzip");
     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    
    "x-newrelic-id: UgEEWV5ACgAFU1NbDg==", 
        "accept-language: en-us",
         "User-Agent: Ghosts%20Dev/1.4.0/ CFNetwork/672.0.8 Darwin/14.0.0"
    ));

     $json =  curl_exec($ch);
     mkdir('json/' . $clan_id);
     file_put_contents('json/' . $clan_id . '/cw.json', $json);
     curl_close($ch);
echo 'Clan Wars json successfully got and saved<br />';

     $jsonurl = 'API_URL'; // Generate API call
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     curl_setopt($ch, CURLOPT_URL, $jsonurl);
     curl_setopt($ch, CURLOPT_ENCODING , "gzip");
     curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    
    "x-newrelic-id: UgEEWV5ACgAFU1NbDg==", 
        "accept-language: en;q=1, el;q=0.9, fr;q=0.8, de;q=0.7, ja;q=0.6, nl;q=0.5",
         "User-Agent: Ghosts%20Dev/1.4.0/ CFNetwork/672.0.8 Darwin/14.0.0",
         "Accept: application/json",
         "bh-client-os: ios",
         "bh-device-ff: tablet",
         "bh-device-res: w768h1024",
         "bh-config-version: 20140426221102532556",
         "bh-os-version: 7.0.4",
         "Connection: keep-alive",
         "bh-title: ghosts",
         "bh-device-dpi: 1",
         "bh-network: xbl",
         "Cookie: token=TOKEN;lang=en",
         "bh-lang: en",
         "Proxy-Connection: keep-alive",
         "Accept-Encoding: gzip, deflate",

    ));

     $json =  curl_exec($ch);
     file_put_contents('json/' . $clan_id . '/roster.json', $json);
     curl_close($ch);
echo 'Roster json successfuly got and saved<br />';

//populate dbs

$url = "http://live.cod.gs/incl/json/" . $clan_id . "/roster.json";

$data = json_decode(file_get_contents($url), true);

     try {
        
        $STH = $DBH->prepare("INSERT INTO clan_8447 (id, username, kills, deaths, wins, losses, kdr) value (:id, :username, :kills, :deaths, :wins, :losses, :kdr)");

        foreach ($data['teamMembers'] as $member) {

       $STH->bindParam(':id', $member['userId']);
        $STH->bindParam(':username', $member['userName']);
        $STH->bindParam(':kills', $member['kills']);
        $STH->bindParam(':deaths', $member['deaths']);
        $STH->bindParam(':wins', $member['wins']);
        $STH->bindParam(':losses', $member['losses']);
        $STH->bindParam(':kdr', $member['kdRatio']);
        
        $STH->execute();
        
        
          }
          echo 'Clan roster successfully populated<br />';
        
    }
    catch (PDOException $e) {
        echo "ERROR: There was a problem saving the roster to the db.";
        
    }

    $url = "http://live.cod.gs/incl/json/" . $clan_id . "/cw.json";

$data = json_decode(file_get_contents($url), true);

     try {
        
        $STH = $DBH->prepare("INSERT INTO nodes_8447 (id, game_mode, shields, delta) value (:id, :game_mode, :shields, 0)");

        $i = 0;

        foreach ($data['targets'] as $target) {

        $i++;

       $STH->bindParam(':id', $i);
        $STH->bindParam(':game_mode', $data['war']['targets'][$i]['game_mode']);
        $STH->bindParam(':shields', $target['shields']);
        
        $STH->execute();
        
        
          }
          echo 'nodes successfully populated<br />';
        
    }
    catch (PDOException $e) {
        echo "ERROR: There was a problem saving the roster to the db.";
        
    }
}

    ?>