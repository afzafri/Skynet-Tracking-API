<?php

#http://www.courierworld.com/scripts/webcourier1.dll/TrackingResultwoheader?nid=1&uffid=&type=4&hawbno=TRACKING

header("Access-Control-Allow-Origin: *"); # enable CORS

if(isset($_GET['trackingNo']))
{
	$trackingNo = $_GET['trackingNo']; # store received GET of tracking number into variable
	$url = "http://www.courierworld.com/scripts/webcourier1.dll/TrackingResultwoheader?nid=1&uffid=&type=4&hawbno=".$trackingNo; # url of skynet tracking page

	$ch = curl_init(); # initialize curl object
	curl_setopt($ch, CURLOPT_URL, $url); # set url
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
	$result = curl_exec($ch); # execute curl, fetch webpage content
	curl_close($ch);  # close curl

	# use regular expression (regex) to parse the html contents. 
	# only fetch the result table, we only want the good stuff
	$patern = '#<table bgcolor="\#dddddd" width=90% cellspacing=2 cellpadding=1>([\w\W]*?)<\/table>#';
	preg_match_all($patern, $result, $parsed);

	# parse the table, get by row
	$trpatern = "#<tr(.*?)<\/tr>#";
	preg_match_all($trpatern, implode($parsed[0],''), $tr);

	print_r($tr[0]);
}

?>