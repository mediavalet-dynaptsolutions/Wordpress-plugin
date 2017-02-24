<?php
/*
Plugin Name: MediaValetTEST
Plugin URI: http://wordpress.org/plugins/MediaValet/
Description: Adds MediaValet assets
Version: 1.0.0
Author: Mediavalet, Inc.
Author URI: https://mediavalet.com
License: GPLv2
*/


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//
// START THE BF FOR EMBEDDING
//
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


function MediaValet_popup_link($atts)  {

  $devOptions = get_option("MediaValetWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $MediaValetAdminOptions[$key] = $option;
  }

  extract( shortcode_atts( array(
    'id' => $MediaValetAdminOptions["MediaValet_url"],
    'branding' => true,
    'collection' => '',
    'query' => '',
    'text' => "&lt;button style='padding: 15px 0px;margin: 10px auto;text-align: center;width: 100%;font-size: 15px;font-weight: bold;color: #333333;background-color: #dde2e6;border: 2px solid #cccccc;border-radius: 4px;'&gt;View our MediaValet&lt;/button&gt;",
    'classes' => ''
    ), $atts )
   );

  if ($collection != '') {
    $url = $id."/".$collection;
  } else {
    $url = $id;
  }

  $elemid = uniqid('bf');
  $output = "<a id='".$elemid."' href='https://mediavalet.com/".$url."' class='".$classes."'>".html_entity_decode($text)."</a>";
  $output .= "<script type='text/javascript'>
      jQuery('#".$elemid."').click(function(e) {
          e.stopImmediatePropagation();
          MediaValet.showEmbed({MediaValet_id: '".$id."', branding: ".$branding.", query: '".$query."', collection_id: '".$collection."'});          
          return false;
      });
    </script>";  
  return $output;
}

add_shortcode('MediaValet', 'MediaValet_popup_link');
add_shortcode('MediaValet', 'MediaValet_popup_link');
add_shortcode('MediaValet-logos', 'MediaValet_popup_link');
add_shortcode('MediaValet-images', 'MediaValet_popup_link');
add_shortcode('MediaValet-documents', 'MediaValet_popup_link');
add_shortcode('MediaValet-people', 'MediaValet_popup_link');
add_shortcode('MediaValet-press', 'MediaValet_popup_link');
add_shortcode('MediaValet-logos', 'MediaValet_popup_link');
add_shortcode('MediaValet-images', 'MediaValet_popup_link');
add_shortcode('MediaValet-documents', 'MediaValet_popup_link');
add_shortcode('MediaValet-people', 'MediaValet_popup_link');
add_shortcode('MediaValet-press', 'MediaValet_popup_link');
add_filter('widget_text', 'do_shortcode');

function add_MediaValet_button() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
     add_filter("mce_external_plugins", "add_MediaValet_tinymce_plugin");
     add_filter('mce_buttons_2', 'register_MediaValet_button');
	
   }
}

	
if (!class_exists("MediaValetWordpressPlugin")) {
  class MediaValetWordpressPlugin {
    var $adminOptionsName = "MediaValetWordpressPluginAdminOptions";
    function MediaValetWordpressPlugin() { //constructor
      
    }
    function init() {
      $this->getAdminOptions();
    }
    //Returns an array of admin options
    function getAdminOptions() {
      $devloungeAdminOptions = array('MediaValet_url' => '');
      $devOptions = get_option($this->adminOptionsName);
      if (!empty($devOptions)) {
        foreach ($devOptions as $key => $option)
          $devloungeAdminOptions[$key] = $option;
      }
      update_option($this->adminOptionsName, $devloungeAdminOptions);
      return $devloungeAdminOptions;
    }
    //Prints out the admin page
    function printAdminPage() {
          if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient permissions to access this page.') );
          }

          $devOptions = $this->getAdminOptions();

          if (isset($_POST['update_MediaValetWordpressPluginSettings'])) { 
            $devOptions['MediaValet_hidebrowser'] = apply_filters('MediaValet_hidebrowser', $_POST['MediaValet_hidebrowser']);
            update_option($this->adminOptionsName, $devOptions);
            ?>
            <div class="updated"><p><strong><?php _e("Settings Updated.", "MediaValetWordpressPlugin");?></strong></p></div>
            <?php
            } ?>
            <div class='wrap'>
              <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
              <h2>MediaValet Plugin Setup</h2>
              <br/>
              <h3>Post/Pages Options</h3>
              <input type='checkbox' id='MediaValet_hidebrowser' name='MediaValet_hidebrowser' value='checked' <?php echo $devOptions['MediaValet_hidebrowser']; ?>> <label for='MediaValet_hidebrowser'>Hide Media Library Option on Pages/Posts</label>

              <div class='submit'>
                <input type="submit" name="update_MediaValetWordpressPluginSettings" value="<?php _e('Update Settings', 'MediaValetWordpressPlugin') ?>" />
              </div>
              </form>
              <hr>
              <br/>
              <div>For help with using this plugin, please visit the <a href='http://help.MediaValet.com/knowledgebase/articles/238392' target='_blank'>MediaValet Knowledge Base</a>.</div>
            </div>
          <?php
        }//End function printAdminPage()


		
    function Main() {
      //echo '<iframe src="https://mediavalet-master.azurewebsites.net/Wordpress.html" style="width: 98%; height: 95%; min-height: 730px;margin-top:10px;"></iframe>';
echo '<iframe src="http://localhost/MasterSDK/wordpress.html" style="width: 98%; height: 95%; min-height: 730px;margin-top:10px;"></iframe>';
	  }

    function ConfigureMenu() {
      add_menu_page("Edit MediaValet", "Edit MediaValet", 6, basename(__FILE__), array(&$dl_pluginSeries,'Main'));
      add_submenu_page( "MediaValet-menu", "Settings", "Settings", 6, basename(__FILE__),  array(&$dl_pluginSeries,'printAdminPage') );
    }     

    function add_settings_link($links, $file) {
    static $this_plugin;
    if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
     
    if ($file == $this_plugin){
      $settings_link = '<a href="admin.php?page=MediaValet-sub-menu">'.__("setup", "MediaValet").'</a>';
       array_unshift($links, $settings_link);
    }
      return $links;
     }
  
  }

} 

if (class_exists("MediaValetWordpressPlugin")) {
  $dl_pluginSeries = new MediaValetWordpressPlugin();
}

//Initialize the admin panel
if (!function_exists("MediaValetWordpressPlugin_ap")) {
  function MediaValetWordpressPlugin_ap() {
    global $dl_pluginSeries;
    if (!isset($dl_pluginSeries)) {
      return;
    }

    add_menu_page("MediaValet", "MediaValet", 6, "MediaValet-menu", array(&$dl_pluginSeries,'Main'), plugin_dir_url(__FILE__)."MV_30x30.png");
	add_submenu_page( "MediaValet-menu", "Settings", "Settings", 6, "MediaValet-sub-menu",  array(&$dl_pluginSeries,'printAdminPage') );

  } 
}

//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//
// START THE BF FOR EMBEDDING
//
//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!


/* PLACE LINK IN WORDPRESS MEDIA BUTTON */
function bf_media_tab($arr) {
  $arr['grabber'] = 'MediaValet';
  return $arr;
}

function bf_grabber($type = 'grabber') {
  media_upload_header();
  bf_browser_manager();
}

function bf_grabber_page() {
  return wp_iframe( 'bf_grabber');
}

function bf_browser_manager() {
  $devOptions = get_option("MediaValetWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $MediaValetAdminOptions[$key] = $option;
  }
  $post_id = isset($_GET['post_id'])? (int) $_GET['post_id'] : 0;
 // $url = "https://mediavaletapps.mediavalet.net/drupal.html&wp_callback_url=".urlencode(plugin_dir_url( __FILE__ ) . 'callback.php?post_id=' . $post_id . '&wp_abspath=' . ABSPATH);
 // $url = "https://mediavalet-master.azurewebsites.net/drupal.html&wp_callback_url=".urlencode(plugin_dir_url( __FILE__ ) . 'callback.php?post_id=' . $post_id . '&wp_abspath=' . ABSPATH);
  //$url = "https://mediavaletapps.mediavalet.net/drupal.html";
   //$url = "https://mediavalet-master.azurewebsites.net/Wordpress.html";
$url = "http://localhost/MasterSDK/wordpress.html";

?>

<script type="text/javascript">
var mvaletwindowurl = 'https://mediavalet-master.azurewebsites.net/Wordpress.html';
var mvaletwindow_host_child = 'https://mediavalet-master.azurewebsites.net';
var mvaletwindow_host_parent='http://localhost:9080/wordpress';
       
var win;
var dialog;
var mvalet_img_counter = 0;
var mvreplacetype = 'd8ckeditor';

	tinymce.init({
		relative_urls: false,
		//alert(relative_urls);
        remove_script_host: false,
		convert_urls : false,
		inline: false,
		plugin: 'custom_link_class',
	 }); 
	tinymce.PluginManager.add( 'custom_link_class', function( editor, url ) {
			
		// Add Button to Visual Editor Toolbar
		editor.addButton('custom_link_class', {
			title: 'Mediavalet',
			image:url + 'MV_16x16.png',
			icon:true,
			close_previous: "yes"
			onclick:function()
			{
			
				try {
                    
                    if (mvalet_img_counter <= 1)
                        mvalet_img_counter = mvalet_img_counter + 1;
                } catch (e) {
                    mvalet_img_counter = 0;
                    return false;
                }
					
				var srcurl=mvaletwindowurl + '?mvaletwindow_host_parent=' + mvaletwindow_host_parent + '&mvreplacetype=' + mvreplacetype;
				
     			});
				window.addEventListener('message', receiveMessage, false);
				function receiveMessage(evt)
				{
					if (evt.origin === mvaletwindow_host_child)
					{
						//alert("got message: "+evt.data);
						var actualdata = evt.data;
                        var actualdataArray;
                        actualdataArray = actualdata.split('||||');
                        
                        InsertHTML(actualdataArray[0]);
                       
					}
				}

              /*   function receiveMessage(evt)
                {
					//var mvaletwindowurl = 'https://mediavaletapps.mediavalet.net/drupal.html';
					var mvaletwindowurl = 'https://mediavalet-master.azurewebsites.net/drupal.html';
					var url_split = mvaletwindowurl.split('/');
					var mvaletwindow_host_child = url_split[0] + "//" + url_split[2]; 
					var mvaletwindow_host_parent=document.location.origin;

                    if (evt.origin === mvaletwindow_host_child)
                    {                                             
                            InsertHTML(evt.data); 
													
                    }
                } */
				
function InsertHTML(file_path)
{
	console.log('file_path=' + file_path);
	console.log('mvalet_img_counter=' + mvalet_img_counter);
	var value = file_path;
	tinymce.activeEditor.insertContent('<img src="' + value + '" />');
                    mvalet_img_counter = 0;
	if (mvalet_img_counter == 1) 
  
	{
		jQuery.ajax({
				 type: 'POST',
				 //url: mvaletwindow_host_parent + '/main',
				url: mvaletwindow_host_parent + '/callback.php',
                 data: {file_path: file_path},
                 success: function (resp) {
					 tinymce.activeEditor.insertContent('<img src="' + resp + '" />');
                     mvalet_img_counter = 0;
                   // dialog_pop.dialog("option", "title", 'Please wait...').dialog("close");
                },
                 error: function (xhr, status, error) {
                 mvalet_img_counter = 0;
                dialog_pop.dialog("option", "title", 'Please wait...').dialog("close");
                 }
				});
		tinymce.activeEditor.insertContent('<img src="' + value + '" />');	
		parent.tinymce.activeEditor.uploadImages(value);   
		parent.tinyMCE.activeEditor.windowManager.close(this);
		mvalet_img_counter = 0;
	}
parent.tinyMCE.activeEditor.windowManager.close(this);	
					
}
</script>
  <div class="wrap" style="height:99%;margin:0px;">
  <iframe src="<?php echo $url; ?>" width="100%" height="100%"></iframe>
  </div>
  
<?php


}

function bf_media_buttons($context) { 
  $img = plugins_url('MV_16x16.png', __FILE__);
  ?>
  <style> .insert-MediaValet-media .wp-media-buttons-icon{ background: url('<?php echo $img ?>') no-repeat 0px 0px; background-size: 100%; } </style>  
    <a href="#" id="MediaValet-add-media" class="button insert-MediaValet-media" style="padding: 1px 0px 0px 3px;">
      <span class="wp-media-buttons-icon" style="vertical-align: text-bottom;"></span></a>
  <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(){
      jQuery(document.body).on('click', '#MediaValet-add-media', function(e) {
        e.preventDefault();
        var media = wp.media;
        media.frames.MediaValet = wp.media.editor.open(wpActiveEditor);
        jQuery( ".media-menu-item:contains('MediaValet')" ).click();
      });
    });
	
  </script> 
<?php
}

function load_into_head() { 
  $devOptions = get_option("MediaValetWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $MediaValetAdminOptions[$key] = $option;
  }
?>
  <style>
    <?php echo $MediaValetAdminOptions["MediaValet_style"]; ?>
  </style>
  <script type="text/javascript">
    function MediaValet_loadScript(src, callback)
    {
      var s,r,t;
      r = false;
      s = document.createElement('script');
      s.type = 'text/javascript';
      s.src = src;
      s.onload = s.onreadystatechange = function() {
        //console.log( this.readyState ); //uncomment this line to see which ready states are called.
        if ( !r && (!this.readyState || this.readyState == 'complete') )
        {
          r = true;
          callback();
		}
      };
      t = document.getElementsByTagName('script')[0];
      t.parentNode.insertBefore(s, t);
    }

     function MediaValet_null() {
     }

	 
    jQuery(document).ready(
      function () {
        MediaValet_loadScript('//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js', MediaValet_null);
    });

  </script>
<?php
}

function MediaValet_scripts() {
  wp_enqueue_script('jquery');
}

//Actions and Filters 
if (isset($dl_pluginSeries)) {

  $devOptions = get_option("MediaValetWordpressPluginAdminOptions");
  if (!empty($devOptions)) {
    foreach ($devOptions as $key => $option)
      $MediaValetAdminOptions[$key] = $option;
  }

  //Actions
  add_action('admin_menu', 'MediaValetWordpressPlugin_ap');
  add_action('MediaValet/MediaValet.php',  array(&$dl_pluginSeries, 'init'));

  if (!isset($devOptions['MediaValet_hidebrowser']) && $devOptions['MediaValet_hidebrowser']!="checked") {
    add_filter('media_upload_tabs', 'bf_media_tab');
    add_action( 'media_buttons', 'bf_media_buttons' );
    add_action( 'media_upload_grabber', 'bf_grabber_page' );
  }

  add_action( 'wp_enqueue_scripts', 'MediaValet_scripts' );
  add_action( 'wp_head', 'load_into_head' );
}
?>