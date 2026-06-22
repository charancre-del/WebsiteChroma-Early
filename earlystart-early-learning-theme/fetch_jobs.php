<?php
$url = "https://app.acquire4hire.com/feed/indeed.xml?id=8154";
$response = file_get_contents($url);
echo $response;
?>


