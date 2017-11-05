<?php
   
$clan_id = $_GET["clan"];


$url = "http://live.cod.gs/incl/json/" . $clan_id . "/roster.json";
$urlcw = "http://live.cod.gs/incl/json/" . $clan_id . "/cw.json";

$clan_db = "clan_" . $clan_id;
$nodes_db = "nodes_" . $clan_id . '';

$data = json_decode(file_get_contents($url), true);
$datacw = json_decode(file_get_contents($urlcw), true);


if (empty($_GET["clan"])) {
    echo 'No clan specified';
} else {
    
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
        
        $STH = $DBH->prepare("UPDATE " . $clan_db . " SET delta = delta + :delta WHERE id = :id;");
        $kdr_delta = $DBH->prepare("UPDATE " . $clan_db . " SET kdr_delta = :kdr_delta  WHERE id = :id;");

        foreach ($data['teamMembers'] as $member) {

       $STH->bindParam(':id', $member['userId']);
       $kdr_delta->bindParam(':id', $member['userId']);

       $stmt = $DBH->prepare("SELECT wins FROM " . $clan_db . " WHERE id=:id");
       $stmt->bindValue(':id', $member['userId']);
       $stmt->execute();
       $past_win = $stmt->fetch(PDO::FETCH_ASSOC);

       echo $past_win['wins'] . ' ';

       $delta = $member['wins'] - (int) $past_win['wins'];

       $stmt2 = $DBH->prepare("SELECT kdr FROM " . $clan_db . " WHERE id=:id");
       $stmt2->bindValue(':id', $member['userId']);
       $stmt2->execute();
       $past_kdr = $stmt2->fetch(PDO::FETCH_ASSOC);

       $kdr_change = round($member['kdRatio'], 4) - round($past_kdr['kdr'], 4);

    

        $STH->bindParam(':delta', $delta);
        $kdr_delta->bindParam(':kdr_delta', $kdr_change);

        
        $STH->execute();
        $kdr_delta->execute();
    }

        $refresh = $DBH->prepare("UPDATE " . $clan_db . " SET kills = :kills,  deaths = :deaths, wins = :wins, losses = :losses WHERE id = :id;");

        foreach ($data['teamMembers'] as $member) {

       $refresh->bindParam(':id', $member['userId']);
        $refresh->bindParam(':kills', $member['kills']);
        $refresh->bindParam(':deaths', $member['deaths']);
        $refresh->bindParam(':wins', $member['wins']);
        $refresh->bindParam(':losses', $member['losses']);
        
        $refresh->execute();
        
        }

        $node_delta = $DBH->prepare("UPDATE " . $nodes_db . " SET shields = :shields,  delta = :delta WHERE id = :id;");
        $get_old_shield = $DBH->prepare("SELECT shields FROM " . $nodes_db . " WHERE id=:id");

        foreach ($datacw['targets'] as $key => $value)
        {
          $node_delta->bindParam(':id', $key);
          $get_old_shield->bindParam(':id', $key);
          $get_old_shield->execute();
          $old_shield = $get_old_shield->fetch(PDO::FETCH_ASSOC);

          $shield_delta = (int) $value['shields'] - (int) $old_shield['shields'];

          $node_delta->bindParam(":shields", $value['shields']);
          $node_delta->bindParam(":delta", $shield_delta);

          $node_delta->execute();
          
        }
          }
        
    catch (PDOException $e) {
        echo "ERROR: There was a problem saving your data. Perhaps you've already submitted your channel?";
        
    }
    
}
?>
 <script src="/js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="/js/bootstrap.js"></script>
   <script>
  $(document).ready(function() {
   var refreshId = setInterval(function() {
      location.reload();
   }, 30000);
   });

    </script>