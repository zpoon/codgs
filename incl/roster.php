<?php 

$clan_id = $_GET["clan"];

if ($clan_id != 5139 and $clan_id != 220302741 and $clan_id != 209782632 and $clan_id != 10242 and $clan_id != 226726140 and $clan_id != 8447) {
  header('Location:  http://live.cod.gs/5139/error');
} else {
   
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
     $win_sum = $DBH->prepare ("SELECT SUM(delta) FROM " . $clan_db);
     $win_sum->execute();
       $sumofwins = $win_sum->fetch(PDO::FETCH_ASSOC);
       $change_kdr = $DBH->prepare ("SELECT SUM(kdr_delta) FROM " . $clan_db);
     $change_kdr->execute();
       $kdr_change = $change_kdr->fetch(PDO::FETCH_ASSOC);

       $countS = $DBH->prepare ("SELECT COUNT(*) FROM " . $clan_db);
       $countS->execute();
       $count = $countS->fetch(PDO::FETCH_ASSOC);

       if ($kdr_change["SUM(kdr_delta)"] > 0)
          {
            $clan_flag = 'text-success"> +';
          }
          elseif ($kdr_change["SUM(kdr_delta)"] < 0) {
            $clan_flag = 'text-danger"> ';

          }
          else {
            $clan_flag = '">';
          }
  }
  catch (PDOException $e) {
        echo "ERROR: There was a problem connecting to the database";
    }
}
   
?>
           <header class="header bg-white b-b b-light">
              <p>Roster tracker</p>
            </header>        
            <section class="scrollable wrapper">
              <div class="m-b-md">
                <h3 class="m-b-none">Live Clan Roster</h3>
              </div>
               <div class="panel b-a">
                        <div class="panel-heading no-border bg-black lt text-center">
                          <a href="#">
                            <i class="fa fa-group fa fa-3x m-t m-b text-white"></i>
                          </a>
                        </div>
                        <div class="padder-v text-center clearfix"> 
                        <div class="col-xs-4">
                            <? echo '
                            <div class="h3 font-bold">' . $count["COUNT(*)"]; ?></div>
                            <small class="text-muted">Roster count</small>
                          </div>                           
                          <div class="col-xs-4 b-r">
                            <div class="h3 font-bold"><? echo number_format($sumofwins["SUM(delta)"]); ?></div>
                            <small class="text-muted">Total Clan Wins</small>
                          </div>
                          <div class="col-xs-4">
                            <? echo '
                            <div class="h3 font-bold ' . $clan_flag . '' . round($kdr_change["SUM(kdr_delta)"], 3) ; ?></div>
                            <small class="text-muted">Clan KDR Change</small>
                          </div>
                        </div>
                      </div>
              <section class="panel panel-default">
                <header class="panel-heading">
                  Clan roster
                  <i class="fa fa-info-sign text-muted" data-toggle="tooltip" data-placement="bottom" data-title="ajax to load the data."></i> 
                </header>
                <div class="table-responsive">
                  <table class="table table-striped m-b-none">
                    <thead>
                      <tr>
                        <th width="25%">Gamertag</th>
                        <th width="10%">Career Kills</th>
                        <th width="10%">Career Deaths</th>
                        <th width="10%">Wins</th>
                        <th width="10%">Losses</th>
                        <th width="10%">KDR</th>
                        <th width="10%">KDR change</th>
                        <th width="25%">Wins this war</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
    
    try {
        
       $sql = 'SELECT username, kills, deaths, wins, losses, kdr, delta, kdr_delta FROM ' . $clan_db . ' ORDER BY delta DESC';
        
        foreach ($DBH->query($sql) as $row2) {

          if ($row2['kdr_delta'] > 0)
          {
            $kdr_flag = 'text-success"> +';
          }
          elseif ($row2['kdr_delta'] < 0) {
            $kdr_flag = 'text-danger"> ';

          }
          else {
            $kdr_flag = '">';
          }


        echo '<tr>';
        echo '<td>' . $row2['username'] . '</td>';
        echo '<td>' . number_format($row2['kills']) . '</td>';
        echo '<td>' . number_format($row2['deaths']) . '</td>';
        echo '<td>' . number_format($row2['wins']) . '</td>';
        echo '<td>' . number_format($row2['losses']) . '</td>';
        echo '<td>' . round($row2['kdr'], 2) . '</td>';
        echo '<td class="font-bold ' . $kdr_flag . '' . $row2['kdr_delta'] . '</td>';
        echo '<td class="font-bold ">' . $row2['delta'] . '</td>';

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
                </div>
            </section>
        