<?php session_start(); ?><html>

<title>Import Google Contacts Using PHP</title>
</head>
<body>

<?php
/*****Form data is stored in session variabe because after google authentication the page will be redirected to this page and data will be persist******/
if (isset($_GET['name'])) {
$_SESSION['name'] = $_GET['name'];
$_SESSION['lname'] = $_GET['lname'];
$_SESSION['email'] = $_GET['email'];
$_SESSION['phone'] = $_GET['phone'];
/****this header part is responsible for google authentication ****/
/****After authentication we get authentication code in return****/
header('location:https://accounts.google.com/o/oauth2/auth?client_id=871406372707-tec4fdkmirfomjlh6lccd4um1ni1jmic.apps.googleusercontent.com&redirect_uri=https://domainscrm.ru/modules/contact/callback.php&scope=https://www.google.com/m8/feeds/&response_type=code');
}
/**session variable is stored in variable****/
$id1 = $_SESSION['name'];
$id2 = $_SESSION['email'];
$id3 = $_SESSION['lname'];
$id4 = $_SESSION['phone'];


$client_id = '871406372707-tec4fdkmirfomjlh6lccd4um1ni1jmic.apps.googleusercontent.com';
$client_secret = 'k9hEJgFORMUbLJAJHj7Ja9SQ';
$redirect_uri = 'https://domainscrm.ru/modules/contact/callback.php' ;
$auth_code = $_GET['code'];



function curl_file_get_contents($url) 
{
	$curl = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
	
	curl_setopt($curl, CURLOPT_URL, $url); //The URL to fetch. This can also be set when initializing a session with curl_init().
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5); //The number of seconds to wait while trying to connect.
	curl_setopt($curl, CURLOPT_USERAGENT, $userAgent); //The contents of the "User-Agent: " header to be used in a HTTP request.
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //To follow any "Location: " header that the server sends as part of the HTTP header.
	curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE); //To automatically set the Referer: field in requests where it follows a Location: redirect.
	curl_setopt($curl, CURLOPT_TIMEOUT, 10); //The maximum number of seconds to allow cURL functions to execute.
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); //To stop cURL from verifying the peer's certificate.
	
	$contents = curl_exec($curl);
	curl_close($curl);
	return $contents;
}



$fields = array(
'code' => urlencode($auth_code),
'client_id' => urlencode($client_id),
'client_secret' => urlencode($client_secret),
'redirect_uri' => urlencode($redirect_uri),
'grant_type' => urlencode('authorization_code')
);
$post = '';
foreach ($fields as $key => $value) {
$post .= $key . '=' . $value . '&';
}
$post = rtrim($post, '&');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
curl_setopt($curl, CURLOPT_POST, 5);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
$result = curl_exec($curl);
curl_close($curl);

$response = json_decode($result);
/***Accessing Access_Token and setting the session for access token so that after refreshing the page it will still persist in page***/

if (isset($response->access_token)) {
$accesstoken = $response->access_token;
$_SESSION['access_token'] = $response->access_token;
}

if (isset($_GET['code'])) {
/**access_token session is passed here for data persist after refreshing the page***/
$accesstoken = $_SESSION['access_token'];
}
if (isset($_REQUEST['logout'])) {
unset($_SESSION['access_token']);
}
$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=' . $max_results . '&oauth_token=' . $accesstoken;
$xmlresponse = curl_file_get_contents($url);
if ((strlen(stristr($xmlresponse, 'Authorization required')) > 0) && (strlen(stristr($xmlresponse, 'Error ')) > 0)) { //At times you get Authorization error from Google.
echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
exit();
}
/**This is main script which is used for contact saving in account,variable declare before is passed here.***/
$access_token = $_SESSION['access_token'];
$contactXML = '<?xml version="1.0" encoding="utf-8"?> '
. '<atom:entry xmlns:atom="http://www.w3.org/2005/Atom" xmlns:gd="http://schemas.google.com/g/2005">'
. ' <atom:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/contact/2008#contact"/> '
. '<gd:name> <gd:givenName>' . $id1 . '</gd:givenName> <gd:fullName></gd:fullName> <gd:familyName>' . $id3 . '</gd:familyName>'
. ' </gd:name> <gd:email rel="http://schemas.google.com/g/2005#home" address="' . $id2 . '"/> '
. '<gd:im address="anurag.tiwari24@gmail.com" protocol="http://schemas.google.com/g/2005#GOOGLE_TALK" primary="true" rel="http://schemas.google.com/g/2005#home"/>'
. ' <gd:phoneNumber rel="http://schemas.google.com/g/2005#home" primary="true">' . $id4 . '</gd:phoneNumber> </atom:entry>';
$headers = array('Host: www.google.com',
'Gdata-version: 3.0',
'Content-length: ' . strlen($contactXML),
'Content-type: application/atom+xml',
'Authorization: OAuth ' . $access_token);
$contactQuery = 'https://www.google.com/m8/feeds/contacts/default/full/';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $contactQuery);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $contactXML);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 400);
curl_setopt($ch, CURLOPT_FAILONERROR, true);
$result = curl_exec($ch);

print_r($result);

?>