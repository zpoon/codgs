<?php

$clan_id = $_GET["clan"];

$token = 'TOKEN';
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
echo 'success';
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