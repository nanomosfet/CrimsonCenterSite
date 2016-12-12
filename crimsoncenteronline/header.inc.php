<?php
include 'login.php';
ini_set("session.save_path","/tmp/");
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Crimson Center for Speech and Language Therapy and Pathology in San Diego</title>
<meta name="keywords" content="Crimson Center for Speech and Language Therapy and Pathology in San Diego " />
<meta name="description" content="The Crimson Center for Speech and Language Therapy and Pathology
      dedicates its efforts to helping the public in San Diego and in the world for the greater good of society" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />
<link href="styles.css" rel="stylesheet" type="text/css" />
<script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAA9DNsLu3g_Kt8778DJ6ni9RStdFhB6oqnI11w_TUSzE2vfJfN3RSBB0EkvoGocIn4sf4f5IduvwWXIg"
            type="text/javascript"></script>

    <script type="text/javascript">
    //<![CDATA[

    var iconBlue = new GIcon();
    iconBlue.image = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
    iconBlue.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconBlue.iconSize = new GSize(12, 20);
    iconBlue.shadowSize = new GSize(22, 20);
    iconBlue.iconAnchor = new GPoint(6, 20);
    iconBlue.infoWindowAnchor = new GPoint(5, 1);

    var iconRed = new GIcon();
    iconRed.image = 'http://labs.google.com/ridefinder/images/mm_20_red.png';
    iconRed.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconRed.iconSize = new GSize(12, 20);
    iconRed.shadowSize = new GSize(22, 20);
    iconRed.iconAnchor = new GPoint(6, 20);
    iconRed.infoWindowAnchor = new GPoint(5, 1);

    var customIcons = [];
    customIcons["restaurant"] = iconBlue;
    customIcons["bar"] = iconRed;

    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());
        map.setCenter(new GLatLng(32.895432, -117.121544), 9);

        GDownloadUrl("phpsqlajax_genxml.php", function(data) {
          var xml = GXml.parse(data);
          var markers = xml.documentElement.getElementsByTagName("marker");
          for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var type = markers[i].getAttribute("type");
            var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")),
                                    parseFloat(markers[i].getAttribute("lng")));
            var marker = createMarker(point, name, address, type);
            map.addOverlay(marker);
          }
        });
      }
    }

    function createMarker(point, name, address, type) {
      var marker = new GMarker(point, customIcons[type]);
      var html = "<b>" + name + "</b> <br/>" + address + "<br/><a href='" + type + "'>Directions to here</a>";
      GEvent.addListener(marker, 'click', function() {
        marker.openInfoWindowHtml(html);
      });
      return marker;
    }
    //]]>
  </script>
</head>
<body onload="load()" onunload="GUnload()">
<div id="main">
<!-- header begins -->
<div id="header">
	
	<div id="logo"><h1></h1>
            <h2></h2>
		<!--<h2><a href="http://www.flash-templates-today.com" title="Free Flash Templates">Design by Free Flash Templates</a></h2>-->
	</div>
	<div id="buttons">
		<ul>
			<li class="first"><a href="index.php"  title="">Home</a></li>
			<li><a href="news.php" title="">News</a></li>
			<li><a href="events.php" title="">Event</a></li>
			<li><a href="staff.php" title="">Staff</a></li>
                        <li><a href="resources.php" title="">Resources</a></li>
                        <li><a href="contact.php" title="">Contact</a></li>
	</ul></div>
	
</div>
<!-- header ends -->

