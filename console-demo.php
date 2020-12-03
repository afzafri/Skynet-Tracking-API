<?php

require 'vendor/autoload.php';

use Afzafri\SkynetTrackingApi;

if (isset($argv[1])) {
	print_r(SkynetTrackingApi::crawl($argv[1]));
} else {
	echo "Usage: " . $argv[0] . " <Tracking code>\n";
}