<?php
<<<<<<< HEAD
ini_set ('display_errors', 1);
$dir='../';
$arg1='Welcome';$arg2='Sign in';$arg4='Create Account';$arg5='Search';$arg6='Home';$arg7='Vansaal';$arg8='Photo';$arg9='RSS';$arg10='About';$arg11='Contact';
$tpl='
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
<title>Zawikawm Photomixer</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
<meta name="robots" content="noindex">
<meta name="Description" content="Biaknalate, Tongdot, Lyric, Dictionary, Music, Tumging, Zomi App" />
<link href="/public/img/limsaal/icon/zawikawm.png" media="all" rel="shortcut icon" type="image/x-icon"/>
<link href="/public/css/bootstrap-responsive.css" media="all" rel="stylesheet">
<link href="/public/css/style.css" media="all" rel="stylesheet">
<link href="/public/css/zawikawm_menu.css" media="all" rel="stylesheet"/>
<script src="/public/js/zawikawm_menu.js" crossorigin="anonymous"></script>
<script src="/public/js/zawikawm_ads.js" crossorigin="anonymous"></script>
<script src="/public/js/zawikawm_tangthacial.js" crossorigin="anonymous"></script>
</head>
<body">
';
/***************************/

$tpl.='<div id="templete_body">
<div id="templete_menu">';
echo preg_replace('/\s+/S', " ", $tpl);

require ($dir.'includes/zawikawm_menu.inc.php');

$tpl='</div>
<div id="templete_header">';
echo preg_replace('/\s+/S', " ", $tpl);

include ($dir.'includes/zawikawm_account.inc.php');

$tpl='</div>
<div id="templete_content">';
echo preg_replace('/\s+/S', " ", $tpl);

//if($page_content !=null)require($page_content);

$tpl='</div>
<div id="templete_tawdap">';
echo preg_replace('/\s+/S', " ", $tpl);

include ($dir.'includes/zawikawm_ads.inc.php');

$tpl='</div>
</div>
<script src="/public/js/zawikawm_eventlistener.js" crossorigin="anonymous"></script>
</body>
</html>
';
echo preg_replace('/\s+/S', " ", $tpl);

/******************************/
$html='<div id="image-container">';
echo formCreate($html."<span id='msg-error'><b>Decorate your profile picture like this to support</b></span>");

function formCreate($html){ $html=
	$html.'
	<form action="connect.php" method="post" enctype="multipart/form-data">
	<input class="button" style="width:300px;height:50px;" type="submit" name="submit" value="Next" />
	'.img_list().'
	</form>';
	return $html;
	}

function img_list(){
$str='
<div class="image">
<input type="radio" class="rdo" checked=true name="checkbox[]" value="01" id="01" class="profile-img"/></label>
<label for="01">Zomi National Day
<img src="upload/01.png" id="01" class="profile-img"></label>
</div>

<div class="image">
<input type="radio" class="rdo" name="checkbox[]" value="02" id="02"/>
<label for="02">Siamsin
<img src="upload/02.png" id="01" class="profile-img"></label>
</div>

<div class="image">
<input type="radio" class="rdo" name="checkbox[]" value="03" id="03"/>
<label for="03">Flag of ZCD(ArtWork)
<img src="upload/03.png" id="04" class="profile-img"></label>
</div>

</div>

<style type="text/css">
#imgresult
	{
	height:250px;
	width:250px;
	float:center;
    border:2px solid #eeccaa;
    -webkit-transition: -webkit-transform 0.5s ease-in-out;
    -moz-transition: -moz-transform 0.5s ease-in-out;
    transition: transform 0.5s ease-in-out;
    -moz-border-radius: 15px;
    border-radius: 15px;
	
	}
img
	{
	height:220px;
	width:100%;
	border:2px solid #eeccaa;
    -webkit-transition: -webkit-transform 0.5s ease-in-out;
    -moz-transition: -moz-transform 0.5s ease-in-out;
    transition: transform 0.5s ease-in-out;
    -moz-border-radius: 15px;
    border-radius: 15px;
	}
.image
	{
	height:260px;
	
	}
.rdo
	{
	font-size:20px;
	}
.profile-img
	{
	width:300;
	height:200;
	}
#image-container
	{
	margin-left:auto;margin-right:auto;width:300px;
    border:1px solid #eeccaa;
	}
#msg-error
	{
	cursor:pointer;color:#10aa10;border-color:solid red;
	}
#msg-req
	{
	cursor:pointer;color:#10aa10;border-color:solid green;
	}
#downloadimg
	{
	background-color:#10dd00;
	color:#fff;
	border:2px solid #00aa00;
	text-decoration:none;
	}
#downloadimg:hover
	{
	background-color:#10dd00;
	color:#fff;
	border:2px solid #00ff00;
	}
</style>';
return $str;
}
?>
=======
header('location:http://zawikawm.com/photomix/');
>>>>>>> efc0d075f01e6faf70d3c552a5b9ca96aadccfc6
