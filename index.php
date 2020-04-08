
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="font/Rimouski.css">
<link rel="stylesheet" href="style.css">
<style>
body {
    font-family: Arial;
}

.list-form-container {
    background: #F0F0F0;
    border: #e0dfdf 1px solid;
    padding: 20px;
    border-radius: 2px;
}

.column {
    float: left;
    padding: 10px 0px;
}

table {
    width: 100%;
    background: #FFF;
}

td, th {
    border-bottom: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    width: auto;
}

.content-div {
    position:relative;
}

.content-div span.column {
    width: 90%;
}

.date {
    position: absolute;
    right: 8px;
    padding: 10px 0px;
}
</style>
</head>
<body style="background-color: #333">

<!-- Clock -->

<canvas id="canvas" width="400" height="400"
style="background-color:#333">
</canvas>

<script>
var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");
var radius = canvas.height / 2;
ctx.translate(radius, radius);
radius = radius * 0.90
setInterval(drawClock, 1000);

function drawClock() {
  drawFace(ctx, radius);
  drawNumbers(ctx, radius);
  drawTime(ctx, radius);
}

function drawFace(ctx, radius) {
  var grad;
  ctx.beginPath();
  ctx.arc(0, 0, radius, 0, 2*Math.PI);
  ctx.fillStyle = 'white';
  ctx.fill();
  grad = ctx.createRadialGradient(0,0,radius*0.95, 0,0,radius*1.05);
  grad.addColorStop(0, '#333');
  grad.addColorStop(0.5, 'white');
  grad.addColorStop(1, '#333');
  ctx.strokeStyle = grad;
  ctx.lineWidth = radius*0.1;
  ctx.stroke();
  ctx.beginPath();
  ctx.arc(0, 0, radius*0.1, 0, 2*Math.PI);
  ctx.fillStyle = '#333';
  ctx.fill();
}

function drawNumbers(ctx, radius) {
  var ang;
  var num;
  ctx.font = radius*0.15 + "px arial";
  ctx.textBaseline="middle";
  ctx.textAlign="center";
  for(num = 1; num < 13; num++){
    ang = num * Math.PI / 6;
    ctx.rotate(ang);
    ctx.translate(0, -radius*0.85);
    ctx.rotate(-ang);
    ctx.fillText(num.toString(), 0, 0);
    ctx.rotate(ang);
    ctx.translate(0, radius*0.85);
    ctx.rotate(-ang);
  }
}

function drawTime(ctx, radius){
    var now = new Date();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    //hour
    hour=hour%12;
    hour=(hour*Math.PI/6)+
    (minute*Math.PI/(6*60))+
    (second*Math.PI/(360*60));
    drawHand(ctx, hour, radius*0.5, radius*0.07);
    //minute
    minute=(minute*Math.PI/30)+(second*Math.PI/(30*60));
    drawHand(ctx, minute, radius*0.8, radius*0.07);
    // second
    second=(second*Math.PI/30);
    drawHand(ctx, second, radius*0.9, radius*0.02);
}

function drawHand(ctx, pos, length, width) {
    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.moveTo(0,0);
    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.rotate(-pos);
}
</script>

<!--___________________________________________________________________________________ -->

<!-- E-Mail -->

<?php
    if (! function_exists('imap_open')) {
        echo "IMAP is not configured.";
        exit();
    } else {
        ?>
    <div id="listData" class="list-form-container">
            <?php

        /* Connecting Gmail server with IMAP */
        $connection = imap_open('{mail.fh-salzburg.ac.at:993/imap/ssl/novalidate-cert}INBOX', 'fhs44506', 'Luki98.salzburgag') or die('Cannot connect to Gmail: ' . imap_last_error());

        /* Search Emails having the specified keyword in the email subject */
        $emailData = imap_search($connection, 'UNSEEN');

        if (! empty($emailData)) {
            ?>
            <table>
            <?php
	$i = 0;
            foreach ($emailData as $emailIdent) {
		if($i ==5) break;
                $overview = imap_fetch_overview($connection, $emailIdent, 0);
                $message = imap_fetchbody($connection, $emailIdent, '1.1');
                $messageExcerpt = substr($message, 0, 150);
                $partialMessage = trim(quoted_printable_decode($messageExcerpt));
                $date = date("d F, Y", strtotime($overview[0]->date));
		$i++;
                ?>
                <tr>
                        <td style="width:15%;"><span class="column"><?php echo $overview[0]->from; ?></span></td>
                        <td class="content-div"><span class="column"><?php echo $overview[0]->subject; ?></span><span class="date"><?php echo $date; ?></span></td>
                </tr>
                <?php
            } // End foreach
            ?>
            </table>
            <?php
        } // end if

        imap_close($connection);
    }
    ?>
    </div>
<!--___________________________________________________________________________________ -->

    <!-- RSS-Reader -->

    <?php
    $q = $_GET["q"];

    if($q == "Google"){
      $xml = ("http://news.google.com/news?ned=us&topic=h&output=rss");
    } elseif($q == "ZDN"){
      $xml = ("https://www.zednet.com/news/rss.xml");
    }

    $xmlDoc = new DOMDocument();
    $xmlDoc->load($xml);

    $channel = $xmlDoc->getElementsByTagName('channel')->item(0);
    $channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
    $channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
    $channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

    echo("<p><a href='" . $channel_link
      . "'>" . $channel_title . "</a>");
    echo("<br>");
    echo($channel_desc . "</p>");

    $x=$xmlDoc->getElementsByTagName('item');
    for ($i=0; $i<=2; $i++) {
      $item_title=$x->item($i)->getElementsByTagName('title')
      ->item(0)->childNodes->item(0)->nodeValue;
      $item_link=$x->item($i)->getElementsByTagName('link')
      ->item(0)->childNodes->item(0)->nodeValue;
      $item_desc=$x->item($i)->getElementsByTagName('description')
      ->item(0)->childNodes->item(0)->nodeValue;
      echo ("<p><a href='" . $item_link
      . "'>" . $item_title . "</a>");
      echo ("<br>");
      echo ($item_desc . "</p>");
    }
     ?>


    <script>
      function showRSS(str){
        if(str.length == 0){
          document.getElementById("rssOutput").innerHTML = "";
          return;
        }
        if(window.XMLHttpRequest){
          xmlhttp = new XMLHttpRequest();
        } else {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
          if(this.readyState==4 && this.status==200){
            document.getElementById("rssOutput").innerHTML=this.responseText;
          }
        }
        xmlhttp.open("GET", "index.php?q="+str, true);
        xmlhttp.send();
      }
    </script>
    </head>
    <body>
      <form>
        <select onchange="showRSS(this.value)";>
        <option value="">Select an RSS-feed:</option>
        <option value="Google">Google News</option>
        <option value="ZDN">ZDNet News</option>
      </select>
      </form>
      <br />
      <div id="rssOutput">
        RSS-feed will be listed here
      </div>

      <!--___________________________________________________________________________________ -->

          <!-- Weather -->

          <div class="container">
        <div class="app-title">
            <p>Weather</p>
        </div>
        <div class="notification"> </div>
        <div class="weather-container">
            <div class="weather-icon">
                <img src="icons/unknown.png" alt="">
            </div>
            <div class="temperature-value">
                <p>- °<span>C</span></p>
            </div>
            <div class="temperature-description">
                <p> - </p>
            </div>
            <div class="location">
                <p>-</p>
            </div>
        </div>
    </div>

    <script src="app.js"></script>

</body>
</html>
