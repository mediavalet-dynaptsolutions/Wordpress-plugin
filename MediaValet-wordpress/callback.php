<?php
uploadImage();
  $parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
  $abspath = $parse_uri[0];
  require_once($abspath . 'wp-load.php');
  require_once($abspath . 'wp-admin/includes/media.php');
  require_once($abspath . 'wp-admin/includes/file.php');
  require_once($abspath . 'wp-admin/includes/image.php');
  require_once($abspath . 'wp-admin/includes/post.php');

  $url = $_REQUEST['attachment_url'];
  if (false === strpos($url, '://')) {
    $url = 'http:' . $url;
  }

  $desc = urldecode($_REQUEST['desc']);

  $attid = "";
  $html = "";
  function new_attachment($att_id){
      global $attid, $image, $html;
      $attid = $att_id;
      // Automatically add as header image:
      if ( isset($_REQUEST['header_image']) ) {
        $p = get_post($att_id);
        update_post_meta($p->post_parent,'_thumbnail_id',$att_id);
        $html = _wp_post_thumbnail_html( $att_id, $_REQUEST['post_id'] );
      }
  }

  function uploadImage(){
	  $curl = curl_init();
// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
 $file_path='https://mediavaletteststorage.blob.core.windows.net/medialibrary-02-d2d059bc-2f81-438c-ac26-3ffcbc265abd-r/6b80bcb4-8648-4510-9168-62fc53a2291e/6b80bcb4-8648-4510-9168-62fc53a2291e/4/lenovo-300-wireless-compact-mouse_01.jpg?sv=2012-02-12&se=2027-02-24T01:07:46Z&sr=b&sp=r&sig=OepD5DkwZdM0ZLZJ06AbuZ5Xq1%2Bd7eKzUNyG%2BJeLJMY%3D';
if (isset($_POST['file_path']) && !empty($_POST['file_path'])) {
            $file_path = $_POST['file_path'];
        }
		
		
		$cookie_name='wordpress_logged_in_bbfa5b726c6b7a9cf3cda9370be3ee91';
		$cookies=$_COOKIE[$cookie_name];
		echo 'WordPresss '.$cookies.'</br><br/>';
		
		
$data = file_get_contents($file_path);
$base64User=base64_encode('mediavalet'.':'.'mediavalet@123') ;
echo $base64User;
curl_setopt_array($curl, array(
  CURLOPT_URL => "http://localhost/wordpress/wp-json/wp/v2/media",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => array(
    "authorization: Basic ".$base64User,
    "cache-control: no-cache",
    "content-disposition: attachment; filename=test.png",
    "content-type: image/png",
  ),
  CURLOPT_POSTFIELDS => $data,
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $file_path.'rrrrrrrrrrrrrrrrrrrrrrrrrrrr'.$response;
}

  }
  
  
  add_action('add_attachment','new_attachment');

  add_filter( 'wp_check_filetype_and_ext', 'bf_filepicker_bypass' );

  function bf_filepicker_bypass( $filearray ) {
      $filearray['type'] = 'image/jpeg';
      $filearray['ext'] = 1;
      return $filearray;
  }

  $image = media_sideload_image($url, $_REQUEST['post_id'], $desc);
  $image = str_replace("src=", "class='size-full wp-image-".$attid."' src=", $image);
  if ( isset($_REQUEST['header_image']) ) {
    $image = "";
  }
  remove_action('add_attachment','new_attachment');

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>MediaValet Callback</title>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script>
      //send it to the editor. Putting it in a timeout, for some reason prevents IE from throwing an ACCESS DENIED error:
      setTimeout(function(){
        parent.parent.wp.media.editor.insert("<? echo $image; ?>");
        jQuery('.media-modal-close').click();
        var html = "<?php echo urlencode($html); ?>";
        if (html) {
          var feature_image = parent.parent.document.getElementById("postimagediv");
          var inner_feature = feature_image.getElementsByClassName("inside")[0];
          inner_feature.innerHTML = decodeURIComponent(html.replace(/\+/g,  " "));
        }
        window.history.back();
      },0);
    </script>
  </head>
  <body>
  </body>
</html>