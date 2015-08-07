<?php 

// Getting the information
$ipaddress = $_SERVER['REMOTE_ADDR'];
$page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
if (!empty($_SERVER['QUERY_STRING'])) {
	$page .= $_SERVER['QUERY_STRING'];
}
$referrer = $_SERVER['HTTP_REFERER'];
$datetime = mktime();
$useragent = $_SERVER['HTTP_USER_AGENT'];
$remotehost = @getHostByAddr($ipaddress);
$nbclickDP = 1;
$data = [
	'ipaddress' => $ipaddress,
	'page' => $page,
	'referrer' => $referrer,
	'datetime' => $datetime,
	'useragent' => $useragent,
	'remotehost' => $remotehost,
	'nbclick DP' => $nbclickDP
];
$current = implode(',', $data);
file_put_contents("tracking.json", $current . "\n" . PHP_EOL, FILE_APPEND);

return $data;