<?php

function getDirContents($dir, &$results = array()){
    $files = scandir($dir);

    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            $results[] = $path;
        } else if($value != "." && $value != "..") {
            getDirContents($path, $results);
            //$results[] = $path;
        }
    }

    return $results;
}
/*
 * --------------------------------------------------------------------
*/
$url = 'http://localhost:8888/add';

$files = array();
$files = getDirContents('images');

foreach($files as $image) {
	echo "$image\n";

	//*
	$filesize = filesize($image);

	$headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
	$postfields = array("image" => "@$image", "filepath" => $image);
	$ch = curl_init();
	$options = array(
	    CURLOPT_URL => $url,
	    CURLOPT_HEADER => true,
	    CURLOPT_POST => 1,
	    CURLOPT_HTTPHEADER => $headers,
	    CURLOPT_POSTFIELDS => $postfields,
	    CURLOPT_INFILESIZE => $filesize,
	    CURLOPT_RETURNTRANSFER => true
	); // cURL options
	curl_setopt_array($ch, $options);
	$server_response = curl_exec($ch);

	echo $server_response;

	if(!curl_errno($ch))
	{
	    $info = curl_getinfo($ch);
	    if ($info['http_code'] == 200)
	        $errmsg = "File uploaded successfully";
	}
	else
	{
	    $errmsg = curl_error($ch);
	}
	curl_close($ch);

	echo $errmsg;
	//*/
}

?>