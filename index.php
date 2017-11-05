<?php 

$clan_id = $_GET["clan"];

$page = $_GET["page"];

if ($clan_id != 5139 and $clan_id != 220302741 and $clan_id != 209782632 and $clan_id != 10242 and $clan_id != 226726140 and $clan_id != 8447) {
  header('Location:  http://live.cod.gs/5139/error');
}

$clan_db = "clan_" . $clan_id;
   
$url = "http://live.cod.gs/incl/json/" . $clan_id . "/cw.json";

$data = json_decode(file_get_contents($url), true);

function multiexplode ($delimiters,$string) {
    
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}

if (isset($data['end_time'])) {

$exploded = multiexplode(array("-","T",":"),$data['end_time']);

} else {
  $exploded = array_fill(0, 7, '0');

}

if ($data['region'] == 1) {
  $exploded[2] .= "-1";
  $exploded[3] = "22";
  $timezone = "EU Region";
}
elseif ($data['region'] == 2) {
  $exploded[3] = "3";
  $timezone = "East Region";
}
else {
  $timezone = "West Region";
}



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

   
?>

<!DOCTYPE html>
<html lang="en" class="app">

<head>
  <meta charset="utf-8" />
  <title>cod.gs WarRoom</title>
  <meta name="description" content="call of duty clan wars tracking" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="/css/icon.css" type="text/css" />
  <link rel="stylesheet" href="/css/font.css" type="text/css" />
  <link rel="stylesheet" href="/css/app.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>

<body class="">
  <section class="vbox">
    <header class="bg-black header header-md navbar navbar-fixed-top-xs box-shadow ">
      <p>
        <b class="label bg-primary m-l-lg">ALPHA RELEASE</b>
        <b class="label bg-dark">War 2 5/16/2014 - Diamond Division</b>
        <? echo '<a href="/' . $clan_id . '/about" class="m-l-lg">About & FAQ</a>'; ?>
      </p>
      <div class="navbar-header aside-md dk">
        <? echo '<a href="/' . $clan_id. '/scorecard" class="navbar-brand">'; ?>
          <img src="/images/logo.png" class="m-r-sm" alt="scale">
          <span class="hidden-nav-xs">cod.gs WarRoom</span>
          </a>
      </div>

    </header>
    <section>
      <section class="hbox stretch">
        <!-- .aside -->
        <aside class="bg-black aside-md hidden-print" id="nav">
          <section class="vbox">
            <section class="w-f scrollable">
              <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="10px" data-railOpacity="0.2">

                <div class="clearfix wrapper dk nav-user hidden-xs">


                  <span class="thumb avatar pull-left m-r">
                      <i class="on md b-black"></i>
                  </span>
                  <span class="hidden-nav-xs clear">
                    <span class="block m-t-xs">
                      <? echo '<strong class="font-bold text-lt">' . $clan_id . '</strong>'; ?>
                        <b class="caret"></b>
                    </span>
                    <span class="text-muted text-xs block">Clan</span>
                  </span>

                </div>


                <!-- nav -->
                <nav class="nav-primary hidden-xs">

                  <ul class="nav nav-main" data-ride="collapse">

                    <li>
                      <? echo '<a href="/' . $clan_id . '/scorecard">'; ?>
                        <i class="i i-stats icon">
                        </i>
                        <span class="font-bold">Live scoreboard</span>
                        </a>
                    </li>
                    <li>
                      <? echo '<a href="/' . $clan_id . '/roster">'; ?>
                        <i class="i i-users2 icon">
                        </i>
                        <span class="font-bold">Roster Tracker</span>
                        </a>
                    </li>
                  </ul>
                  <div class="line dk hidden-nav-xs"></div>
                  <div class="text-muted text-xs hidden-nav-xs padder m-t-sm m-b-sm">Scorecard</div>

                  <div id="scores">
                    <img src="/images/load.gif" style="display: block; margin-left: auto; margin-right: auto" id="spinner">
                  </div>
                  </ul>
                  <div class="text-muted text-xs hidden-nav-xs padder m-t-sm m-b-sm">End of day (
                    <? echo $timezone; ?>)</div>
                  <div class="daycount m-b-lg m-t-m h5"></div>
                  <div class="text-muted text-xs hidden-nav-xs padder m-t-sm m-b-sm">End of war (
                    <? echo $timezone; ?>)</div>
                  <div class="warcount m-b-lg m-t-m h5"></div>
            </section>

            <footer class="footer hidden-xs no-padder text-center-nav-xs">
              <a href="#nav" data-toggle="class:nav-xs" class="btn btn-icon icon-muted btn-inactive m-l-xs m-r-xs">
                <i class="i i-circleleft text"></i>
                <i class="i i-circleright text-active"></i>
              </a>
            </footer>
          </section>
        </aside>
        <!-- /.aside -->
        <section id="content">
          <section class="vbox">
            <section class="scrollable wrapper">
              <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-info-sign"></i>
                <strong>Testers:</strong> Public screenshots/streams of this tool is now
                <strong>allowed</strong> for remaining duration of the war. Public sharing/linking of the URL is
                <strong>still not allowed</strong>. Thank you for your help this weekend.
              </div>
              <div id="results"></div>
              <footer id="footer">
                <div class="padder clearfix">
                  <p>
                    <small>cod.gs War Room service
                      <br>&copy; 2014. All data provided live from Call of Duty servers. Call of Duty is a registered trademark
                      of Activision Publishing, Inc.</small>
                  </p>
                </div>
              </footer>
              </div>
              </div>
              </div>

              <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
            </section>
          </section>
        </section>
      </section>

      <script src="/js/jquery.min.js"></script>
      <!-- Bootstrap -->
      <script src="/js/bootstrap.js"></script>
      <!-- App -->

      <script src="/js/app.js"></script>

      <script src="/js/datatables/jquery.dataTables.min.js"></script>
      <script src="/js/datatables/populate.js"></script>
      <script src="/js/charts/sparkline/jquery.sparkline.min.js"></script>
      <script src="/js/slimscroll/jquery.slimscroll.min.js"></script>
      <script src="/js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
      <script src="/js/app.plugin.js"></script>
      <script src="/js/charts/flot/jquery.flot.min.js"></script>
      <script src="/js/charts/flot/jquery.flot.tooltip.min.js"></script>
      <script src="/js/charts/flot/jquery.flot.resize.js"></script>
      <script src="/js/charts/flot/jquery.flot.orderBars.js"></script>
      <script src="/js/charts/flot/jquery.flot.pie.min.js"></script>
      <script src="/js/charts/flot/jquery.flot.grow.js"></script>
      <script src="/js/charts/flot/demo.js"></script>
      <link rel="stylesheet" type="text/css" href="/js/countdown/jquery.countdown.css">
      <script type="text/javascript" src="/js/countdown/jquery.plugin.js"></script>
      <script type="text/javascript" src="/js/countdown/jquery.countdown.js"></script>
      <script>
        $(document).ready(function () {
          $('.warcount').countdown({
            until: new Date(Date.UTC( << ? echo $exploded[0].
              ', '.$exploded[1].
              '-1, '.$exploded[2].
              ', '.$exploded[3].
              ', '.$exploded[4].
              ', '.$exploded[5]; ? > )),
            format: 'HMs'
          });
          $('.daycount').countdown({
            until: new Date(Date.UTC( << ? echo $exploded[0].
              ', '.$exploded[1].
              '-1, '.$exploded[2].
              ', '.$exploded[3].
              ', '.$exploded[4].
              ', '.$exploded[5]; ? > )),
            format: 'HMs'
          });
          $("#results").load("/incl/<?php echo $_GET["
            page "]; ?>.php?clan=<?php echo $_GET["
            clan "]; ?>");
          $("#scores").load("/incl/scores.php?clan=<?php echo $_GET["
              clan "]; ?>"),
            function () {

              $('#scores').fadeIn('slow');
              $('#spinner').html('');
            };
          var refreshId = setInterval(function () {
            $("#results").load("/incl/<?php echo $page; ?>.php?clan=<?php echo $clan_id; ?>");
            $("#scores").load("/incl/scores.php?clan=<?php echo $_GET["
              clan "]; ?>");
            $('.easypiechart').easyPieChart;
          }, 30000);

        });
      </script>
</body>

</html>