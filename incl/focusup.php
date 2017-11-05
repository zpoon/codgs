<?php

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
   
     $clan_id = $_POST["clan"];
$clan_db = "nodes_" . $clan_id; 

if (isset($_POST["id1"]) && isset($_POST["id2"]) && isset($_POST["id3"]) && isset($_POST["id4"]) && isset($_POST["id5"]) && isset($_POST["id6"]) && isset($_POST["id1"]) && isset($_POST["id8"])) {

echo 'we have liftoff';

  $focus[0] = $_POST["id1"];
  $focus[1] = $_POST["id2"];
  $focus[2] = $_POST["id3"];
  $focus[3] = $_POST["id4"];
  $focus[4] = $_POST["id5"];
  $focus[5] = $_POST["id6"];
  $focus[6] = $_POST["id7"];
  $focus[7] = $_POST["id8"];

  $update_focus = $DBH->prepare("UPDATE " . $clan_db . " SET focus = :focus WHERE id = :id;");

  $j = 0;

  foreach ($focus as $fup) {

    $j++;

    $update_focus->bindParam(':id', $j);
    $update_focus->bindParam(':focus', $fup);

    $update_focus->execute();

  }
}
?>
The focus has been successfully updated.