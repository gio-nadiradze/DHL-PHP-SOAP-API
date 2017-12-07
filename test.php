<?php
require_once('lib/nusoap.php');
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
$client = new nusoap_client('http://2.240.0.13:8090/OrderReqService.asmx?WSDL', 'wsdl',
						$proxyhost, $proxyport, $proxyusername, $proxypassword);
$client->soap_defencoding = 'UTF-8';						
$client->decode_utf8 = false;

$err = $client->getError();
if ($err) {
	echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

$milis=time()+(60*60*4);

$token=hash("sha256",mb_convert_encoding($milis."aYfW\bgRWT]i_eed\Ph_dgUU12345","UTF-16LE"));
// Doc/lit parameters get wrapped
$AuthData= array("UserName"=>"12345","Token"=>$token,"RequestTimeMilis"=>$milis);

/*
$param = array('auth' =>$AuthData,"trackingNumber"=>"123456");
$result = $client->call('TrackParcel', array('parameters' => $param), '', '', false, true);*/


$param = array('auth' =>$AuthData);
$result = $client->call('GetServerTime', array('parameters' => $param), '', '', false, true);
//$result = $client->call('GetCities', array('parameters' => $param), '', '', false, true);
// Check for a fault
if ($client->fault) {
	echo '<h2>Fault</h2><pre>';
	print_r($result);
	echo '</pre>';
} else {
	// Check for errors
	$err = $client->getError();
	if ($err) {
		// Display the error
		echo '<h2>Error</h2><pre>' . $err . '</pre>';
	} else {
		// Display the result
		echo '<h2>Result</h2><pre>';
		print_r($result);
		echo '</pre>';
	}
}
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';

?>