 <?php 

$clan_id = $_GET["clan"];

if ($clan_id != 5139 and $clan_id != 220302741 and $clan_id != 209782632 and $clan_id != 10242 and $clan_id != 226726140 and $clan_id != 8447) {
  header('Location:  http://live.cod.gs/5139/error');
}

$url = "http://live.cod.gs/incl/json/" . $clan_id . "/cw.json";

$data = json_decode(file_get_contents($url), true);

if ($data['clans'][1]['clan_id'] == null) {
  echo '<div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>The data we recieved from the Call of Duty servers is not what we expected. The API might be experiencing problems, the scoreboard will return shortly.</p>
                  </div>';
                }
                else {


$sorted_clans = $data;
                   
usort($sorted_clans['clans'], function($a, $b) {
    return $b['clan_points'] - $a['clan_points'];
    });
 echo '<ul class="nav">';
   foreach ($sorted_clans['clans'] as $clan) {


    echo '<li>';
    echo '<a href="#">';
    echo '<b class="label bg-white text-black pull-right">' . $clan['clan_points'] . '</b>';
    echo '<i class="text-warning-dk text-xs">' . $clan['tag'] . '</i>';
    echo '<span>' . $clan['name'] . '</span>';
    echo '</a>';
    echo '</li>';

    $counter = $counter + 1;
   }
   echo '</ul>';
 }
?>  