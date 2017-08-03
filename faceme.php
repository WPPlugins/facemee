<?php

/*
Plugin Name: FaceMe
Plugin URI: http://tecnocratas.org/faceme
Description: FaceMe is a wordpress plugin that allow you to add a facebook "add me" badge on your wordpress blog.
Author: Samuel 'Logik' Sanchez
Version: 0.1.0
Author URI: http://tecnocratas.org
*/

/**
* FaceMe WordPress Plugin
*/



/////////////////////////////////////////////////////////////////////////////

if (!defined('ABSPATH')) {
	return ;
	}

if (!class_exists('faceme')) {

/////////////////////////////////////////////////////////////////////////////

/**
* "faceme" WordPress Plugin
*/
Class faceme {

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	function init() {

		// attach the handler
		//
		add_action('wp_head',
			array('faceme', 'wp_head'));
		add_action('wp_footer',
			array('faceme', 'wp_footer'));


		// attach to admin menu
		//
		if (is_admin()) {
			add_action('admin_menu',
				array('faceme', '_menu')
				);
			}
		
		// attach to plugin installation
		//
		register_activation_hook(
			__FILE__,
			array('faceme', 'install')
			);

		// plugin updated, upgrade it
		//
		if (version_compare(faceme::version(), get_option('faceme_version')) > 0) {
			faceme::install();
			}
		}
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	function wp_head() {
		return faceme::facemerize(__FUNCTION__);
		}

	function wp_footer() {
		return faceme::facemerize(__FUNCTION__);
		}


	function facemerize($tag) {
		$faceme_settings = (array) get_option('faceme_settings');
		if (isset($faceme_settings['snippets'][$tag])) {
			echo $faceme_settings['snippets'][$tag];
			}
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 


	function install() {

		// settings
		//
$plug_url = plugins_url('wp-faceme/faceme-style.css');
$header_cont = '<link href="'.$plug_url.'" rel="stylesheet" type="text/css" />';
		$faceme_settings = array(
			'snippets' => array(
				'wp_head' => $header_cont,
				'wp_footer' => '<div class="faceme_r"><div class="faceme" style="background-color:#59B7FF" onclick="window.open(\'http://facebook.com/wpburn\')"><noscript><center>JS<br />is<br />off</center></noscript></div><div class="ili_right" onclick="window.open(\'http://tecnocratas.org/faceme\')" ><noscript><a href="http://tecnocratas.org/faceme">Get it</a></noscript></div><div class="ili_left" onclick="window.open(\'http://tecnocratas.org/faceme\')" ><noscript><a href="http://tecnocratas.org/faceme">Get it</a></noscript></div></div>',
				'culoare' => '59B7FF',
				'facebookacc' => 'samuelsanchez',
				'margintop' => '150',
				'leftright' => '_r',
				'facemeus' => 'faceme'
				)
			);
		
		if ($old_faceme_settings = get_option('faceme_settings')) {
			update_option(
				'faceme_settings', array_merge(
					$faceme_settings,
					$old_faceme_settings
					)
				);
			} else {
			add_option(
				'faceme_settings', $faceme_settings
				);
			}

		// version
		//
		if (get_option('faceme_version')) {
			update_option(
				'faceme_version', faceme::version()
				);
			} else {
			add_option(
				'faceme_version', faceme::version(), ' ', 'no'
				);
			}
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --
	

	function _menu() {
		add_submenu_page('options-general.php',
			 'faceme: faceMe',
			 'faceMe', 8,
			 __FILE__,
			 array('faceme', 'menu')
			);
		}
		

	function menu() {

		// sanitize referrer
		//
		$_SERVER['HTTP_REFERER'] = preg_replace(
			'~&saved=.*$~Uis','', $_SERVER['HTTP_REFERER']
			);
		
		// information updated ?
		//
		if ($_POST['submit']) {

			$_ = $_POST['faceme_settings'];
			$_['snippets'] = array_map('stripCSlashes', $_['snippets']);
			
			// save
			//
			update_option(
				'faceme_settings',
				$_
				);

			die("<script>document.location.href = '{$_SERVER['HTTP_REFERER']}&saved=settings:" . time() . "';</script>");
			}

		// operation report detected
		//
		if (@$_GET['saved']) {
			
			list($saved, $ts) = explode(':', $_GET['saved']);
			if (time() - $ts < 10) {
				echo '<div class="updated"><p>';
	
				switch ($saved) {
					case 'settings' :
						echo 'Settings saved.';
						break;
					}
	
				echo '</p></div>';
				}
			}

		// read the settings
		//
		$faceme_settings = (array) get_option('faceme_settings');

?>

<script language="javascript" type="text/javascript">
function checkwitchischecked(){
if(document.getElementById("sidecheckphp").value == "_l"){
	document.getElementById("leftright_l").checked = true;
	}else{
		document.getElementById("leftright_r").checked = true;
		}
	}
	
function checkwitchischeckedtwitter(){
if(document.getElementById("twittercheckphp").value == "faceus"){
	document.getElementById("facemeus_us").checked = true;
	}else{
		document.getElementById("facemeus_me").checked = true;
		}
	}
	
function faceme_me_check(){
	var facemeus_me_js = document.getElementById("facemeus_me");
	if (facemeus_me_js.checked = true){
		twitterascrie = "faceme";
		document.getElementById("twittercheckphp").value ="faceme";
		}
	}
function faceme_us_check(){
	var facemeus_us_js = document.getElementById("facemeus_us");
	if (facemeus_us_js.checked = true){
		twitterascrie = "faceus";
		document.getElementById("twittercheckphp").value ="faceus";
		}
	}
	
function lsidecheck(){
	var leftside_js = document.getElementById("leftright_l");
	if (leftside_js.checked = true){
		sidescrie = "_l";
		document.getElementById("sidecheckphp").value ="_l";
		}
	}
function rsidecheck(){
	var rightside_js = document.getElementById("leftright_r");
	if (rightside_js.checked = true){
		sidescrie = "_r";
		document.getElementById("sidecheckphp").value ="_r";
		}
	}

function faceme_update() {
	ilimarginf = document.getElementById("margintop").value;
	ilimargin =  parseFloat(ilimarginf) + 117;
	margintop_js = document.getElementById("margintop").value;
	twitterascrie = document.getElementById("twittercheckphp").value;
	sidescrie = document.getElementById("sidecheckphp").value;
	div_class = twitterascrie + sidescrie;
	var partea1 = '<div class="' + div_class + '"><div class="faceme" style="background-color:#';
	var partea2 = document.getElementById("culoare").value + '; top:' + margintop_js + 'px;' ;
	var partea3 = '" onclick="window.open(\'http://facebook.com/';
	var twitter_username =  document.getElementById("facebookacc").value;									  
	var partea4 = '\')">';
	var partea5 = '<noscript><center>JS<br />is<br />off</center></noscript></div><div class="ili_right" style="top:' + ilimargin + 'px;" onclick="window.open(\'http://tecnocratas.org/faceme\')" ><noscript><a href="http://tecnocratas.org/faceme">Get it</a></noscript></div><div class="ili_left" style="top:' + ilimargin + 'px;"  onclick="window.open(\'http://tecnocratas.org/faceme\')" ><noscript><a href="http://tecnocratas.org/faceme">Get it</a></noscript></div></div>';
	faceme_all_options = partea1 + partea2 + partea3 + twitter_username + partea4 + partea5;
	document.getElementById("faceme_footer_options").value = faceme_all_options;
	
}
function set_color(val){
	var culoare_get = val;
	document.getElementById("culoare").value = val;
	faceme_update();
	}
</script>
<script src="<?php echo plugins_url('wp-faceme/jscolor.js');?>" type="text/javascript"></script>
<div class="wrap">
	<h2>faceMe</h2>
	<p>
<h3>Settings for faceMe badge</h3>
	</p>
<br />
	<form method="post" name="faceme_form" action="<?php $_SERVER['php_self']  ?>">




<label><strong>Set badge color :</strong></label>
<input autocomplete="off" class="color" name="faceme_settings[snippets][culoare]" id="culoare" type="text" value="<?php echo $faceme_settings['snippets']['culoare']; ?>" onchange="faceme_update();" />
<span style="font-size:0.9em; color:#666">(click on the text field to set another color)</span>


<br /><br />
<label><strong>Your facebook account :</strong></label> 
<input name="faceme_settings[snippets][facebookacc]" id="facebookacc" type="text" value="<?php echo $faceme_settings['snippets']['facebookacc']; ?>" onchange="faceme_update();" />
<br /><br />
<label><strong>Distance from top (in pixels) :</strong></label> 
<input name="faceme_settings[snippets][margintop]" id="margintop" type="text" value="<?php echo $faceme_settings['snippets']['margintop']; ?>" onchange="faceme_update();" /> px
<br /><br />
<label><strong>Side:</strong></label> 
  <label>
    <input type="radio" name="sidebun" value="_l" id="leftright_l" onchange="lsidecheck(); faceme_update();" />
    Left</label>
  <label>
    <input type="radio" name="sidebun" value="_r" id="leftright_r" onchange="rsidecheck(); faceme_update();" />
    Right</label>
  <br />

  <textarea style="display:none;" name="faceme_settings[snippets][wp_head]" cols="15" rows="1" id="wp_head_html"><link href="<?php echo plugins_url('wp-faceme/faceme-style.css');?>" rel="stylesheet" type="text/css" /></textarea>
            
       
		<textarea style="display:none;" name="faceme_settings[snippets][wp_footer]" cols="50" rows="10" id="faceme_footer_options"><?php echo $faceme_settings['snippets']['wp_footer']; ?></textarea>
		<input style="display:none;" name="faceme_settings[snippets][leftright]" id="sidecheckphp" type="text" value="<?php echo $faceme_settings['snippets']['leftright']; ?>" />
        <input style="display:none;" name="faceme_settings[snippets][facemeus]" id="twittercheckphp" type="text" value="<?php echo $faceme_settings['snippets']['facemeus']; ?>" />
        

     
  <p class="submit" style="text-align:left;"><input onclick="faceme_update();" type="submit" name="submit" value="Save &raquo;"  /></p>
        <p><input style="background-color:#3B5998" name="submit" type="submit" value="Use the default color!" onclick="set_color('3B5998'); faceme_update();"/></p>
	</form>
    
 <script language="javascript" type="text/javascript">
 checkwitchischeckedtwitter();
 checkwitchischecked();
 </script>  
</div>
<?php
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	Function version() {
		if (preg_match('~Version\:\s*(.*)\s*~i', file_get_contents(__FILE__), $R)) {
			return trim($R[1]);
			}
		return '$Rev: 40918 $';
		}
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	//--end-of-class
	}

}

/////////////////////////////////////////////////////////////////////////////


faceme::init();

/////////////////////////////////////////////////////////////////////////////


?>