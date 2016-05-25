<?php
session_start();
echo '
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8"/>
<title>Zawikawm Photomixer</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0">
<meta name="robots" content="noindex">
<meta name="Description" content="Biaknalate, Tongdot, Lyric, Dictionary, Music, Tumging, Zomi App" />
<link href="http://zawikawm.com/public/img/limsaal/icon/zawikawm.png" media="all" rel="shortcut icon" type="image/x-icon"/>
<link href="http://zawikawm.com/public/css/bootstrap-responsive.css" media="all" rel="stylesheet">
<link href="http://zawikawm.com/public/css/style.css" media="all" rel="stylesheet">
</head>
<body">
';


if(isset($_POST['checkbox']))
{
$chk=$_POST['checkbox'];
if($chk)
	{
	for($i=0;$i<sizeof($chk);$i++)
	{
		$chk=$chk[$i];
	}
		
	//$rdr='https://www.facebook.com/dialog/oauth?client_id=1714491162120189&scope=public_profile,publish_actions,user_location&redirect_uri=http://localhost/connect.php';
	$_SESSION["pic_id"] = $chk; // Picture ID from Index page
	$_SESSION["zkid"]=uniqid();
	echo '
	<div style="width: 300px;position: relative;margin-left:auto;margin-right:auto;">
	<div class="option_img" style="width: 300px;position: absolute;">
	<img style="max-width:300px;max-height:300px;" id="profileImage"/>
	</div>
	<div class="option_img" style="position: absolute;">
	<img style="width:300px;height:300px;" id="selectedImage" src="upload/'.$chk.'.png" />
	</div>
	
	<div style="position:relative;opacity:1;top:300px;">
	<input id="mix" type="button" class="button" style="width:300px;height:50px;" value="Login with Facebook" onclick="Login();" />
	<form action="http://app-zawikawm07.rhcloud.com/connect.php" method="post" enctype="multipart/form-data">
	<input id="next" type="submit" class="button" style="width:300px;height:50px;display:none;" name="submit" value="Make as facebook profile picture" />
	<input type="text" class="rdo" name="base64[]" id="fbimage" value="" style="width:0px;height:0px;display:none;"/>
	</form>
	</div>
	</div>';	
	}
}
else if(isset($_SESSION['zkid']) && isset($_POST['base64']))
{
	$chk=$_POST['base64'];
	if($chk)
	{
		for($i=0;$i<sizeof($chk);$i++)
		{
			$chk=$chk[$i];
		}		
			$file = 'upload/upload.jpg';		
			$data = explode(',', $chk);
			//$data = str_replace('data:image/jpg;base64,', '', $data);
			//$data = str_replace(' ', '+', $data);
			//$ifp = fopen($file, "wb"); 		
			//fwrite($ifp, base64_decode($data[1])); 
			//fclose($ifp); 		
			//file_put_contents($file, base64_decode($data[1]));//save to file
			//file_get_contents('upload/'.$chk.'.jpg',true);
			$data = base64_decode($data[1]); // base64 decoded image data
			$source_img = imagecreatefromstring($data);
			//$source_img = imagerotate($source_img, 45, 0); // rotate with angle 90 here
			
		if (file_exists($file))
		{
			  $html="<span id='msg-error'><b> Please try again another user is active...<a href='http://zawikawm.com/photomix/'>Back</a></b></span>";
				echo $html;
				exit;
		}
		else
		{
			$imageSave = imagejpeg($source_img, $file, 100);
			imagedestroy($source_img);// destroy from memory
			//$uploadimgname=$_FILES["file"]["name"];
			//$data = file_get_contents($remotefile,true);
			
			//file_put_contents("upload/upload.jpg", $data);
			//echo "Stored in: " . "upload/upload.jpg";
			 
			resize($file, $file, 300, 300);
			copy($file,"upload/mix.jpg");
			unlink($file);//delete upload file
			generateImage($_SESSION["pic_id"],"upload/mix.jpg");
			FBSDK("upload/mix.jpg");
			 
			//echo $html;//output download image and link
		}
	}
	else
	{
		echo "<span id='msg-error'><b>Photo too large or Invalid. Lim lemtang lo hi.</b></span>";
	}
}

else
{
	if(isset($_REQUEST['code']))
	{
	FBSDK("upload/mix.jpg");
	}
	else
	{	
	echo "Somthing worng for parameter";
	}
}

	
function resize($source_image, $destination, $tn_w, $tn_h, $quality = 100, $wmsource = false)
{
    $info = getimagesize($source_image);
    $imgtype = image_type_to_mime_type($info[2]);

    #assuming the mime type is correct
    switch ($imgtype) {
        case 'image/jpeg':
            $source = imagecreatefromjpeg($source_image);
            break;
        case 'image/gif':
            $source = imagecreatefromgif($source_image);
            break;
        case 'image/png':
            $source = imagecreatefrompng($source_image);
            break;
        default:
           die('Invalid Image Type.');
    }

    #Figure out the dimensions of the image and the dimensions of the desired thumbnail
    $src_w = imagesx($source);
    $src_h = imagesy($source);


    #Do some math to figure out which way we'll need to crop the image
    #to get it proportional to the new size, then crop or adjust as needed

    $x_ratio = $tn_w / $src_w;
    $y_ratio = $tn_h / $src_h;

    if (($src_w <= $tn_w) && ($src_h <= $tn_h)) {
        $new_w = $src_w;
        $new_h = $src_h;
    } elseif (($x_ratio * $src_h) < $tn_h) {
        $new_h = ceil($x_ratio * $src_h);
        $new_w = $tn_w;
    } else {
        $new_w = ceil($y_ratio * $src_w);
        $new_h = $tn_h;
    }

    $newpic = imagecreatetruecolor(round($new_w), round($new_h));
    imagecopyresampled($newpic, $source, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
    $final = imagecreatetruecolor($tn_w, $tn_h);
    //$backgroundColor = imagecolorallocate($final, 255, 255, 255);
	$backgroundColorAlpha = imagecolorallocatealpha($final, 255, 255, 255,100);
	
	imagealphablending($final, false);  //[note2]
	imagefill($final, 0, 0, $backgroundColorAlpha);
	//imagefilledrectangle ($final, $tn_w, $tn_w, imagesx($final),  imagesy($final),$backgroundColorAlpha);
	imagealphablending($final, true);  //If you use it

	
    //imagecopyresampled($final, $newpic, 0, 0, ($x_mid - ($tn_w / 2)), ($y_mid - ($tn_h / 2)), $tn_w, $tn_h, $tn_w, $tn_h);
    imagecopy($final, $newpic, (($tn_w - $new_w)/ 2), (($tn_h - $new_h) / 2), 0, 0, $new_w, $new_h);

    #if we need to add a watermark
    if ($wmsource) {
        #find out what type of image the watermark is
        $info    = getimagesize($wmsource);
        $imgtype = image_type_to_mime_type($info[2]);

        #assuming the mime type is correct
        switch ($imgtype) {
            case 'image/jpeg':
                $watermark = imagecreatefromjpeg($wmsource);
                break;
            case 'image/gif':
                $watermark = imagecreatefromgif($wmsource);
                break;
            case 'image/png':
                $watermark = imagecreatefrompng($wmsource);
                break;
            default:
                die('Invalid Image Type.');
        }

        #if we're adding a watermark, figure out the size of the watermark
        #and then place the watermark image on the bottom right of the image
        $wm_w = imagesx($watermark);
        $wm_h = imagesy($watermark);
        imagecopy($final, $watermark, $tn_w - $wm_w, $tn_h - $wm_h, 0, 0, $tn_w, $tn_h);

    }
    if (imagejpeg($final, $destination, $quality)) {
        return true;
    }
    return false;
	
}

function generateImage($chk,$file)
{

//define the width and height of our images
    define("WIDTH", 320);
    define("HEIGHT", 320);
    $dest_image = imagecreatetruecolor(WIDTH, HEIGHT);
    //make sure the transparency information is saved
    imagesavealpha($dest_image, false);
    //create a fully transparent background (127 means fully transparent)
    $trans_background = imagecolorallocatealpha($dest_image, 255,255, 255, 127);
	$white_background = imagecolorallocate($dest_image,255,255,255);
	//imagealphablending($dest_image, false);  //[note2]
	//imagefilledrectangle ($dest_image, 0, 0, imagesx($dest_image),  imagesy($dest_image),$white_background);
	//imagealphablending($dest_image, true);  //If you use it
    //fill the image with a transparent background
    imagefill($dest_image, 0, 0, $white_background);

    //take create image resources out of the 3 pngs we want to merge into destination image
    //$a = imagecreatefrompng('1.png');
    //$b = imagecreatefrompng('2.png');
    //$c = imagecreatefrompng('3.png');
			
    $a = imagecreatefromstring(file_get_contents($file));
	//$b = imagecreatefromstring(file_get_contents('5.png'));
    $c = imagecreatefromstring(file_get_contents('upload/'.$chk.'.png'));
		
    //copy each png file on top of the destination (result) png
    imagecopymerge($dest_image, $a, 0, 0, 0, 0, WIDTH, HEIGHT,100);
    //imagecopy($dest_image, $b, 0, 0, 0, 0, WIDTH, HEIGHT);
    imagecopymerge($dest_image, $c, 0, 0, 0, 0, WIDTH, HEIGHT,40);

    //send the appropriate headers and output the image in the browser
    //header('Content-Type: image/png');
	//imagecopymerge($a, $frame, 0, 0, 0, 0, 50, 50, 100);
	//save to file
	//imagejpeg($dest_image, 'image.jpg');
	//direct to browser
	//imagepng($dest_image);
	//$image=imagecreatefromjpeg($_GET['img']);
	//printf('<img src="data:image/png;base64,%s"/>',base64_encode(file_get_contents('image.jpg')));
			
	//Allocate a color tor the text
	$white=imagecolorallocate($dest_image,255,255,255);
	//Set Path to font file
	//$font_path='comic.ttf';
	//Set Text to be print on image
	//$text='Hello';
	//Print text on image (font-size;rotate;left;top)
	//imagettftext($dest_image,20,0,10,280,$white,$font_path,$text);
			
	ob_start();
	imagepng($dest_image);
	$img=base64_encode(ob_get_clean());
	//$imageSave = imagejpeg($img, $file, 100);
	file_put_contents($file, base64_decode($img));//save to file
	//printf('<center><img id="imgresult" class="profile-img" src="data:image/png;base64,%s"/></center>',$img);
	//$html='<center><a id="downloadimg" href="data:image/png;base64,'.$img.'" download="profile-photo-create-by-zawikawm.com.png">Download Photo</a></center>';
			
    //destroy all the image resources to free up memory
    imagedestroy($a);
    //imagedestroy($b);
    imagedestroy($c);
    imagedestroy($dest_image);
			
	/*
	$imageName = '2.png';
	$im_src = create_image_from_type($imageName);
	$size = getimagesize($imageName);
	$im_dst = create_image_from_type($imageName);
	$white = imagecolorallocate($im_dst, 255, 255, 255);
	imagecolortransparent($im_dst, $white);
	imagefilledrectangle($im_dst, 0, 0, $size[0], $size[1], $white);
	$opacityVal = 50;// put the opacity value here
	imagecopymerge($im_dst, $im_src, 0, 0, 0, 0,$size[0], $size[1], $opacityVal);
	//save_image($im_dst, $imageName, 100);
	imagedestroy($im_dst);
	imagedestroy($im_src);
	*/
	//return $html;
}

function FBSDK($mix)
{
//Facebook SDK Begin
		require_once('Facebook/autoload.php' );//include facebook api library
		######### edit details ##########
		$appId = '1714491162120189'; //Facebook App ID
		$appSecret = '965e4556c4a984932a55f443bfafab11'; // Facebook App Secret
		$return_url = 'http://app-zawikawm07.rhcloud.com/connect.php';  //return url (url to script)
		$homeurl = 'http://zawikawm.com/photomix/index.php';  //return to home
		$fbPermissions = 'publish_actions,public_profile,email,user_friends, user_photos';  //Required facebook permissions
		##################################
				
		if(isset($_SESSION["zkid"]))
		{
		$PicLocation = $mix;// Picture ID from Index page
		}

		$fb = new Facebook\Facebook([
		  'app_id' => $appId,
		  'app_secret' => $appSecret,
		  'default_graph_version' => 'v2.4'
		]);

		//try to get access token 
		try{
			$helper = $fb->getRedirectLoginHelper();
			$session = $helper->getAccessToken();	
		}catch(FacebookRequestException $ex){
			die(" Facebook Message: " . $ex->getMessage());
		} catch(Exception $ex){
			die( " Message: " . $ex->getMessage());
		}
			
		//get picture ready for upload
		$data = ['message' => '','source' => $fb->fileToUpload($PicLocation)];
		
		//try upload photo to facebook wall
		$graph_node='';	
		if($session)
		{
			try{
				$photo_response = $fb->post('/me/photos', $data, $session);
				$graph_node = $photo_response->getGraphNode();
			} catch(FacebookRequestException $ex){
				die(" Facebook Message: " . $ex->getMessage());
			} catch(Exception $ex){
				die( " Message: " . $ex->getMessage());
			}
		}
		else{
			//if login requires redirect user to facebook login page
			$login_url = $helper->getLoginUrl($return_url, array('scope'=> $fbPermissions));
			header('Location: '. $login_url);
			exit();
		}
		if(isset($graph_node["id"]) && is_numeric($graph_node["id"]))
		{	
			//href="http://www.facebook.com/profile.php?preview_cover='.$graph_node["id"].'"
			echo "<script>window.top.location.href='http://www.facebook.com/photo.php?fbid=".$graph_node["id"]."&type=1&makeprofile=1&makeuserprofile=1';</script>";
		}
		else
		{
			//Nothing
		}		
		//Facebook SDK end

}
?>
</div>
<div id="fb-root"></div>
<div id="account-info"></div>
<script>
window.fbAsyncInit = function () {
  FB.init({
    appId: '1714491162120189',
    status: true,
    cookie: true,
    xfbml: true
  });
};

(function (doc) {
  var js;
  var id = 'facebook-jssdk';
  var ref = doc.getElementsByTagName('script')[0];
  if (doc.getElementById(id)) {
    return;
  }
  js = doc.createElement('script');
  js.id = id;
  js.async = true;
  js.src = "https://connect.facebook.net/en_US/all.js";
  ref.parentNode.insertBefore(js, ref);
  
  
}(document));


function Login() {
  FB.login(function (response) {
    if (response.authResponse) {
      FB.api('/me', function (response) {
		/*
        document.getElementById("displayName").innerHTML = response.name;
        document.getElementById("userName").innerHTML = response.username;
        document.getElementById("userID").innerHTML = response.id;
        document.getElementById("userEmail").innerHTML = response.email;
		*/
        FB.api('/me/picture?width=300&height=300', function (response) {
        document.getElementById("profileImage").setAttribute("src", response.data.url);
		document.getElementById("selectedImage").setAttribute("style", "width:300px;height:300px;opacity:0.3;position: absolute;");
		document.getElementById("mix").style.display="none";
		document.getElementById("next").style.display="block";

		Next(response.data.url,function(dataUri){
		document.getElementById("fbimage").setAttribute("value", dataUri);
		});
		  });				

      });
	  
    } else {
      alert("Login attempt failed!");
    }
  }, { scope: 'email,user_photos,publish_actions,public_profile,user_friends' });
};



function PostMessage() {
  FB.api('/me/feed', 'post', {
    message: "Test"
  });
}
function Logout() {
  FB.logout(function () { document.location.reload(); });
}

FB.Event.subscribe('auth.authResponseChange', function (response) {
  if (response.status === 'connected') {
    alert("Successfully connected to Facebook!");
  }
  else if (response.status === 'not_authorized') {
    alert("Login failed!");
  } else {
    alert("Unknown error!");
  }
});

function Next(url,callback){
//location.href="/connect.php?zkid="+value;
var xhr = new XMLHttpRequest();
    xhr.responseType = 'blob';
    xhr.onload = function() {
        var reader  = new FileReader();
        reader.onloadend = function () {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.send();	
}
</script>
