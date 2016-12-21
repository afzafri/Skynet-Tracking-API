<?php

/*  Skynet Tracking API created by Afif Zafri.
    Tracking details are fetched directly from Skynet tracking website,
    parse the content, and return JSON formatted string.
    Please note that this is not the official API, this is actually just a "hack",
    or workaround for implementing Skynet tracking feature in other project.
    Usage: http://site.com/api.php?trackingNo=CODE , where CODE is your tracking number
*/

header("Access-Control-Allow-Origin: *"); # enable CORS

if(isset($_GET['trackingNo']))
{
	$trackingNo = $_GET['trackingNo']; # store received GET of tracking number into variable
	$url = "http://www.courierworld.com/scripts/webcourier1.dll/TrackingResultwoheader?nid=1&uffid=&type=4&hawbno=".$trackingNo; # url of skynet tracking page

	$ch = curl_init(); # initialize curl object
	curl_setopt($ch, CURLOPT_URL, $url); # set url
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); # receive server response
	$result = curl_exec($ch); # execute curl, fetch webpage content
	$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE); # receive http response status
	curl_close($ch);  # close curl

	# use regular expression (regex) to parse the html contents. 
	# only fetch the result table, we only want the good stuff
	$patern = '#<table bgcolor="\#dddddd" width=90% cellspacing=2 cellpadding=1>([\w\W]*?)<\/table>#';
	preg_match_all($patern, $result, $parsed);

	# parse the table, get by row
	$trpatern = "#<tr(.*?)<\/tr>#";
	preg_match_all($trpatern, implode($parsed[0],''), $tr);

	# parse and store only the date into an array.
	# skynet html table does not store the date in column, but in row. 
	# so we need to fetch the row, and store into column (hope this make sense lol)
	$dateArray = array();

	if(count($tr[0]) > 0) # check if there is records found or not
	{
		for($i=0;$i<count($tr[0]);$i++)
		{
			# check if the string only contains the date
			if(strpos($tr[0][$i], '<tact>') === false)
			{
				# use regex to parse
				$datepatern = "#<b>(.*?)</b>#";
				preg_match_all($datepatern, $tr[0][$i], $dateparsed);
				$dateArray[$i] = strip_tags($dateparsed[0][0]); # store the date into new array
			}
		}
	
		# rearrange array index, and shift the index by 1
		$dateArray = array_combine(range(1, count($dateArray)), array_values($dateArray));
	}

	# parse the tracking table, get only the good stuff, and store into and associative array
	$trackres = array();
	$trackres['http_code'] = $httpstatus; # set http response code into the array
	$j = 0; # index for accessing date array
	
	if(count($tr[0]) > 0) # check if there is records found or not
	{
		$trackres['message'] = "Record Found"; # return record found if number of row > 0

		for($i=0;$i<count($tr[0]);$i++)
		{
			# check if the string contains the date
			if(strpos($tr[0][$i], '<tact>') === false)
			{
				# increase the index when we found string with date
				$j++;
			}

			# check if the string not contains the date
			if(strpos($tr[0][$i], '<tact>') !== false)
			{
				# parse the table by column <td>
		        $tdpatern = "#<td>(.*?)</td>#";
		        preg_match_all($tdpatern, $tr[0][$i], $td);
		        
		        # store into variable, strip_tags is for removing html tags
	            $process = strip_tags($td[0][0]);
	            $time = strip_tags($td[0][1]);
	            $location = strip_tags($td[0][2]);
	            $date = $dateArray[$j];

	            # store into associative array
	            $trackres['data'][$i]['date'] = $date;
	            $trackres['data'][$i]['time'] = $time;
	            $trackres['data'][$i]['process'] = $process;
	            $trackres['data'][$i]['location'] = $location;
			}
		}
	}
	else
	{
		$trackres['message'] = "No Record Found"; # return record not found if number of row < 0
        # since no record found, no need to parse the html furthermore
	}

	# add project info into the array
    $trackres['info']['creator'] = "Afif Zafri (afzafri)";
    $trackres['info']['project_page'] = "https://github.com/afzafri/Skynet-Tracking-API";
    $trackres['info']['date_updated'] =  "21/12/2016";

	# output/display the JSON formatted string
    echo json_encode($trackres);
}

?>