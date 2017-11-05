<?php 

$clan_id = $_GET["clan"];

if ($clan_id != 5139 and $clan_id != 220302741 and $clan_id != 209782632 and $clan_id != 10242 and $clan_id != 226726140 and $clan_id != 8447) {
  header('Location:  http://live.cod.gs/5139/error');
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
   
$clan_db = "nodes_" . $clan_id;  

}
?>
Use this page to "highlight" a node on the scorecard. This is useful for setting a node for you clan to attack/defend.<br /> <strong>Warning: there is no authentication on this page, and anyone will this link will be able to change the focus.</strong>
 <section class="panel panel-default">

                <header class="panel-heading">
                  War nodes
                  <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i> 
                </header>
                <div class="table-responsive">
                  <form role="form" id="form2" method="POST" action="">
                    <input type="text" class="clan_id" name="clan" id="clan" placeholder="clan id" readonly>
                  <table class="table table-striped m-b-none">
                    <thead>
                      <tr>
                        <th width="25%">Game Type</th>
                        <th width="10%">Wins</th>
                        <th width="10%">Change</th>
                        <th width="10%">Focus</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
    
    try {
        
       $sql = 'SELECT id, game_mode, shields, delta, focus FROM ' . $clan_db;

        $j =0;
        foreach ($DBH->query($sql) as $row2) {

        $j++;

          if ($row2['focus'] == 1) {

            $focus_val[$j] = "$('yes" . $j . "').prop('checked', true);";

            $button_string =  '<div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-sm btn-success active">
                        <input type="radio" class="yes' . $row2['id'] . '" name="id' . $row2['id'] . '" id="id' . $row2['id'] . '" value="1"><i class="fa fa-check text-active"></i> Yes
                      </label>
                      <label class="btn btn-sm btn-danger">
                        <input type="radio" class="no' . $row2['id'] . '" name="id' . $row2['id'] . '" id="id' . $row2['id'] . '" value="0"><i class="fa fa-check text-active"></i> No
                      </label>
                    
                    </div>';
          }
          else {
            $focus_val[$j] = "$('no" . $j . "').prop('checked', true);";
            $button_string =  '<div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-sm btn-success">
                        <input type="radio" class="id' . $row2['id'] . '" name="id' . $row2['id'] . '" id="id' . $row2['id'] . '" value="1"><i class="fa fa-check text-active"></i> Yes
                      </label>
                      <label class="btn btn-sm btn-danger active">
                        <input type="radio" class="id' . $row2['id'] . '" name="id' . $row2['id'] . '" id="id' . $row2['id'] . '" value="0"><i class="fa fa-check text-active"></i> No
                      </label>
                    
                    </div>';
          }


        echo '<tr>';
        echo '<td>' . $row2['game_mode'] . '</td>';
        echo '<td>' . $row2['shields'] . '</td>';
        echo '<td>' . $row2['delta'] . '</td>';
        echo '<td>' . $button_string . '</td>';

        echo '</tr>';
    }
        $DBH = null;
      
        
    }
    catch (PDOException $e) {
        echo "ERROR: There was a database problem";
        
    } 
    ?>
    </section>
                    </tbody>
                  </table>
<button type="submit" class="btn btn-block m-r-lg m-t-md btn-primary" onclick="sendForm()" id="Updatepost">Save focus changes</button>
<div class="successmsg alert alert-info hidden"></div>
</form> 
 </div>
<script>
$('.clan_id').val(<? echo $clan_id; ?>);
<? foreach ($focus_val as $fval) {
  echo $fval;
} 
?>
$("#form2").submit(function (e) {
     e.preventDefault();
     $.ajax({
         type: "POST",
         url: "/incl/focusup.php",
         data: $("#form2").serialize(),
         success: function (data) {
             $('.successmsg').removeClass('hidden');
             $(".successmsg").text(data);
         }
     });
 });
</script>
               
                    
                    