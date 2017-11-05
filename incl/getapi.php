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
        "accept-language: en-us",
         "User-Agent: Ghosts%20Dev/1.4.0/ CFNetwork/672.0.8 Darwin/14.0.0"
    ));

	 $json =  curl_exec($ch);
	 file_put_contents('json/' . $clan_id . '/cw.json', $json);
	 curl_close($ch);
echo 'success';
	 ?>
    <script src="/js/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/js/bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            var refreshId = setInterval(function () {
                location.reload();
            }, 30000);
        });
    </script>