<?php
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use GuzzleHttp\Client;

//Skapa en HHTP-client
$client = new Client();

//anropa URL: http://unicorns.idioti.se/
$url = 'http://unicorns.idioti.se/';
if(isset($_GET['id'])) {
  $url .= "/".$_GET['id'];
}

$res = $client->request('GET', $url,  [
    'headers' => [
        'Accept'     => 'application/json',
    ]
]);

//Omvandla JSON-svar till datatyper
$data = json_decode($res->getBody());


$log = new Logger('Laboration 1');
$log->pushHandler(new StreamHandler('greetings.log', Logger::INFO));

//Log if user is checking specific unicorn or all
if(isset($_GET['id'])) {
  $log->info("Unicorns LOG: Request info about: ".$data->name);
} else {
  $log->info("Unicorns LOG: Request info about: all unicorns");
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Unicorns!</title>
    <link href="background.css" type="text/css" rel="stylesheet">
  </head>
  <body>
    <!--Title of the webpage -->
    <div>
      <h1>The fantastic unicorn database!</h1><br>
    </div>

    <!--Write out info about different unicorns -->
    <div>
      <?php
      if(isset($_GET['id'])) {
        echo("<button onclick=\"location.href='index.php'\"  style='margin-left: 100px;'>ALL UNICORNS!!</button>");
        echo "<h3>".$data->name."</h3>";
        echo "<p>".$data->spottedWhen->date."</p>";
        echo "<p>".$data->description."</p>";
        echo "<p><strong>Personen som kikat på djuret:</strong> ".$data->reportedBy."</p>";
        echo "<img src='$data->image'/>";

      } else {
          echo "<h3>List of unicorns</h3>";
          echo "<ol>";
            foreach ($data as $key => $value) {
              echo "<li>";
                echo "<h2>".$value->name;
                echo("<button onclick=\"location.href='index.php?id=$value->id'\"  style='float: right;'>GO UNICORNS!</button>");
                echo "</h2>";
                echo "<hr>";
              echo "</li>";
            }
          echo "</ol>";
      }
      ?>

    </div>
  </body>
</html>
