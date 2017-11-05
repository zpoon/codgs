<?php 


$clan_id = $_GET["clan"];

if ($clan_id != 5139 and $clan_id != 220302741 and $clan_id != 209782632 and $clan_id != 10242 and $clan_id != 226726140 and $clan_id != 8447) {
  header('Location:  http://live.cod.gs/5139/error');
} else {

$clan_db = "clan_" . $clan_id;
$nodes_db = "nodes_" . $clan_id . '';

$url = "http://live.cod.gs/incl/json/" . $clan_id . "/cw.json";


$data = json_decode(file_get_contents($url), true);

if ($data['clans'][1]['clan_id'] == null) {
  header('Location:  http://live.cod.gs/' . $clan_id . '/malform');
  exit;
}

foreach ($data['war']['targets'] as &$mode) {

  switch ($mode['game_mode']) {

    case "grnd":
        $mode['game_mode'] = "Drop Zone";
        break;
    case "cranked":
        $mode['game_mode'] = "Cranked";
        break;
    case "dom":
        $mode['game_mode'] = "Domination";
        break;
        case "war":
        $mode['game_mode'] = "Team Deathmatch";
        break;
        case "war hc":
        $mode['game_mode'] = "HC Team Deathmatch";
        break;
        case "sr":
        $mode['game_mode'] = "Search and Rescue";
        break;
        case "conf":
        $mode['game_mode'] = "Kill Confirmed";
        break;
        case "conf hc":
        $mode['game_mode'] = "HC Kill Confirmed";
        break;
}
}

function time_elapsed_B($secs){
    $bit = array(
        ' year'        => $secs / 31556926 % 12,
        ' week'        => $secs / 604800 % 52,
        ' day'        => $secs / 86400 % 7,
        ' hour'        => $secs / 3600 % 24,
        ' minute'    => $secs / 60 % 60,
        ' second'    => $secs % 60
        );
        
    foreach($bit as $k => $v){
        if($v > 1)$ret[] = $v . $k . 's';
        if($v == 1)$ret[] = $v . $k;
        }
    $ret[] = 'ago';
    
    return join(' ', $ret);
    }

$nowtime = time();   

$time_elapsed = time_elapsed_B($nowtime-$data['now_epoch']);

}

?>
           <header class="header bg-white b-b b-light">
              <p>Live war scoreboard</p>
            </header>        
              <div class="row">

          
                  <div class="col-sm-12">
                    <div class="text-right text-left-xs">
                      <div class="m-b-xs">
                        <span class="text-uc">Last updated:</span>
                        <?php echo '<div class="h5 m-n"><strong>' . $time_elapsed . '</strong></div>'; ?>
                      </div>                      
                    </div>
                  </div>
                </div>
              <div class="row">
                <div class="col-sm-6">
                   <section class="panel panel-default">
                    <header class="panel-heading">Overview</header>
                    <table class="table table-striped m-b-none">
                      <thead>
                        <tr>
                          <th width="140">Health</th>
                          <th>Wins</th>
                          <th>Node</th>
                          <th>Controller</th>                      
                          <th width="80"><i class="fa fa-exchange"></i></th>
                          <th><i class="fa fa-crosshairs"></i></th>
                        </tr>
                      </thead>
                      <tbody>
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

    try {
      

        $get_delta = $DBH->prepare('SELECT delta FROM ' . $nodes_db . ' WHERE id = :id');

$delta2[] = array();

foreach ($data['targets'] as $key => $value)
{
    $get_delta->bindParam(':id', $key);
  $get_delta->execute();
  $deltas = $get_delta->fetch(PDO::FETCH_ASSOC);


  $delta2[$key] = (int) $deltas['delta'];
  $delta_flag[] = array();

if ((int) $deltas['delta'] > 0)
          {
            $delta_flag[$key] = 'text-success"><i class="i  i-arrow-up"></i> +';
            $delta_text[$key] = 'text-success">Defense';
          }
          elseif ((int) $deltas['delta'] < 0) {
            $delta_flag[$key] = 'text-danger"><i class="i  i-arrow-down"></i> ';
            $delta_text[$key] = 'text-danger">Offense';

          }
          else {
            $delta_flag[$key] = '"">';
            $delta_text[$key] = '">Neutral';

          }
}
}
catch (PDOException $e) {
        echo "ERROR: There was a problem connecting to the database";
    }

$count = array();
foreach($data['clans'] as $clan)
{
    @$count[$clan['focus']]++;
}

$i = 0;
   foreach ($data['targets'] as $node) {
$i++;
if ($node['shields'] >= $node['threshold'])
   {
   $progflag = "progress-striped";
   $progcolor = "bg-primary";
   }
   else {
   $progflag = "";
   $progcolor = "bg-dark";
   }
    echo '<tr>';
    echo '<td><div class="progress progress-sm ' . $progflag . ' active m-t-xs m-b-none">';
    echo '<div class="progress-bar ' . $progcolor . '" data-toggle="tooltip" data-original-title="' . $node['shields'] . '" style="width:' . ($node['shields'] / $node['threshold']) * 100 . '%"></div></td>';
    echo '<td class="font-bold">' .  $node['shields'] . '</td>';
    echo '<td>' . $data['war']['targets'][$i]['game_mode'] . '</span>';
    echo '<td>' . $data['clans'][$node['clan_owner_number']]['name']. '</td>';
    echo '<td class="font-bold ' . $delta_flag[$i] . '' . $delta2[$i] . '</td>';
    echo '<td class="font-bold text-info">' . $count[$i] . '</td></tr>';
   }
?>  
                    
                      </tbody>
                    </table>
                  </section>
                </div>
                <div class="col-md-3">
                    <div class="timeline">

                      <? 

                      $events = array_reverse($data['events']);

                      if (count($events) == 0) {
                        echo '<span class="text-center">There are no capture or War events to list just yet...</span>';
                      }
                      else {

                      if (count($events) < 3) {
                        $x = count($events);
                      }
                      else {
                        $x = 3;
                      }

                      for ($i = 0; $i <= $x; $i++) {

                        if ($events[$i][1]['ev_type'] == '1') {
                          $ev_bg = 'bg-success';
                          $ev_icon = 'fa-flag';
                          $arrow = 'right';
                          $alt = 'alt';
                          $ev_string = 'Capture';
                        }
                        elseif ($events[$i][1]['ev_type'] == '2') {
                          $ev_bg = 'bg-danger';
                          $ev_icon = 'fa-warning';
                          $arrow = 'left';
                          $alt = '';
                          $ev_string = 'Neutral';
                        }
                        else {
                          $ev_bg = 'bg-dark';
                          $ev_icon = 'fa-question';
                          $arrow = 'right';
                          $alt = 'alt';
                          $ev_string = '?';
                         
                        }


                      echo '<article class="timeline-item ' . $alt .'">';
                          echo '<div class="timeline-caption">';            
                          echo '<div class="panel panel-default">';
                          echo '<div class="panel-body">';
                          echo '<span class="arrow ' . $arrow . '"></span>';
                          echo '<span class="timeline-icon"><i class="fa ' . $ev_icon . ' time-icon ' . $ev_bg . '"></i></span>';
                          echo '<span class="timeline-date text-xs">' . time_elapsed_B($nowtime-$events[$i][0]) . '</span>';
                          echo '<h5>' . $data['war']['targets'][$events[$i][1]['value']]['game_mode'] .'</h5>';
                          echo '<p>' . $data['clans'][$events[$i][1]['actor']]['name'] .'</p>';
                          echo '</div></div></div></article>'; 

                        }

                        }?>
                  </div>
              </div>
                 <div class="col-md-3">
                   <section class="panel panel-default">
                    <div class="panel-body">
                      <div class="clearfix text-center m-t">
                        <div class="inline">
                            <div class="thumb-lg">
                      
                          </div>
                          <? echo '<div class="h4 m-t m-b-xs">' . $clan_id . '</div>'; ?>
                          <small class="text-muted m-b">Tracked clan</small>
                        </div>                      
                      </div>
                    </div>
                    <footer class="panel-footer bg-black text-center">
                      <div class="row pull-out">
                        <div class="col-xs-4">
                          <div class="padder-v">
                            <?  
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
    echo '
                            <span class="m-b-xs h3 block text-white">' . $count["COUNT(*)"] . '</span>
                            <small class="text-muted">Members</small>
                          </div>
                        </div>
                        <div class="col-xs-4 dk">
                          <div class="padder-v">
                            <span class="m-b-xs h3 block text-white">' . number_format($sumofwins["SUM(delta)"]) . '</span>
                            <small class="text-muted">War wins</small>
                          </div>
                        </div>
                        <div class="col-xs-4">
                          <div class="padder-v">
                            <span class="m-b-xs h3 block ' . $clan_flag . '' . round($kdr_change["SUM(kdr_delta)"], 3) . '</span> '; ?>
                            <small class="text-muted">KDR change</small>
                          </div>
                        </div>
                      </div>
                    </footer>
                  </section>
                </div>
              </div>
              <?php 
              $n = 1;
              for ($x=1; $x<=3; $x++) {
                echo '

               <div class="row">';
               for ($k=1; $k<=3; $k++) {
                if ($data['targets'][$n] != null) {


                $winningclanid = $data['targets'][$n]['clan_owner_number'];

              echo '

              <div class="col-lg-4">
                  <section class="panel b-a">
                  <div class="panel-heading no-border block bg-black lt">
                      <span class="pull-right badge dk m-t-sm">' . $data['targets'][$n]['clan_points'] . ' CP</span>
                      <a href="#" class="h4 text-lt m-t-sm m-b-sm block font-bold">' . $data['war']['targets'][$n]['game_mode'] . '</a>
                    </div>';
                    $i = 0;
                     foreach ($data['clans'] as $clan) {
                      $i++;
  if ($clan['focus'] == $n)
   {
   echo ' <div class="panel-heading no-border block bg-info">
                  <i class="fa fa-crosshairs icon">
                        </i>
                      <a href="#" class="h6 text-lt m-t-sm m-b-sm font-bold">' . $data['clans'][$i]['name'] . ' is active on this node</a>
                    </div>';
   }
 }
                    echo ' <div class="panel-body">

                      <span class="pull-right text-muted">' . $data['war']['targets'][$n]['reward_exp_percent'] . '%</span>
                      <a href="#" class="block m-b text-ellipsis">XP reward</a>
                      <span class="pull-right text-muted">' . $data['targets'][$n]['threshold'] . '</span>
                      <a href="#" class="block m-b text-ellipsis">Threshold</a>
                      <div id="b-c" class="text-center">
                        <div class="easypiechart inline m-b m-t" data-percent="' . round(($data['targets'][$n]['shields'] / $data['targets'][$n]['threshold']) * 100, 0) .'" data-bar-color="#f9c842" data-line-width="16" data-loop="false" data-size="188" data-animate="1000">
                          <div>
                            <span class="h1 m-l-sm step"></span>%
                            <div class="text text-muted text-lg">'. $data['targets'][$n]['shields'] . '</div>
                          </div>
                        </div>
                      </div>
                      <div class="row text-center m-t">
                        <div class="col-xs-6">
                          <p>Activity</p>
                          <h3 class="font-thin '. $delta_text[$n] . '</h3>
                        </div>
                        <div class="col-xs-6">
                          <p>Change</p>
                          <h3 class="font-thin">' . $delta2[$n] . '</h3>
                        </div>
                      </div>
                    </div>
                    <div class="clearfix panel-footer">
                  
                      <table class="table table-striped m-b-none">
                      <tbody>
  ';
  $j = 0;
  $winningclanid = $data['targets'][$n]['clan_owner_number'];
  $data['targets'][$n]['progress'][$winningclanid] = $data['targets'][$n]['shields'];




  foreach ($data['targets'][$n]['progress'] as $key => $value) {
    $wins_left = $data['targets'][$n]['threshold'] - $value;

    if ($wins_left < 0) {
      $wins_left = 0;
    }
$j++;

if ($key == $winningclanid) {
  $winning_bg = 'bg-primary';
  $label_bg = 'label-primary';
}
else {
  $winning_bg = 'bg-dark';
  $label_bg = 'label-dark';
}
echo '
                      
                        <tr> 
                         <td><span class="label ' . $label_bg .' lr v-middle">' . $value . '</span></td>
                          <td class="font-bold">' . $wins_left . '</td>                   
                          <td width="180">
                            <div class="progress progress-sm m-b-none m-r-lg">
                              <div class="progress-bar ' . $winning_bg .'" data-toggle="tooltip" data-original-title="' . $value . '" style="width: ' . ($value / $data['targets'][$n]['threshold']) * 100 . '%"></div>
                            </div>
                          </td>
                          <td>' . $data['clans'][$key]['name'] . '</td>
                         
                        </tr>
                      ';
                    } }

                    echo '</tbody></table>                   
                    </div>
                  </section>
                </div>';
                $n++;
               }


                echo '</div>';
              }?>
                
                    
            </section>
          </section>

          <script src="/js/app.plugin.js"></script>
            <script src="/js/charts/flot/demo.js"></script>
       