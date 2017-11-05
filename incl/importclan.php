<?php

   if (empty($_GET["clan"])) {
    echo 'No clan specified';
} else {

$clan_id = $_GET["clan"];

    $url = "http://live.cod.gs/incl/json/" . $clan_id . "/roster.json";

$clan_db = "clan_" . $clan_id;

$data = json_decode(file_get_contents($url), true);


    
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
    
    try {
        
        $STH = $DBH->prepare("INSERT INTO clan_10242 (id, username, kills, deaths, wins, losses, kdr) value (:id, :username, :kills, :deaths, :wins, :losses, :kdr)");

        foreach ($data['teamMembers'] as $member) {

       $STH->bindParam(':id', $member['userId']);
        $STH->bindParam(':username', $member['userName']);
        $STH->bindParam(':kills', $member['kills']);
        $STH->bindParam(':deaths', $member['deaths']);
        $STH->bindParam(':wins', $member['wins']);
        $STH->bindParam(':losses', $member['losses']);
        $STH->bindParam(':kdr', $member['kdRatio']);
        
        $STH->execute();
        
        echo 'Your data has been successfuly submitted for approval<br />';
          }
        
    }
    catch (PDOException $e) {
        echo "ERROR: There was a problem saving your data. Perhaps you've already submitted your channel?";
        
    }
    
}
?>