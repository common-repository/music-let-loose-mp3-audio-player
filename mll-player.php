<?php
/**
 * @package MLL Audio Player MP3 Ajax
 * @version 1
 */
/*
Plugin Name: MLL Audio Player MP3 Ajax
Plugin URI: http://musicletloose.org
Description: Play mp3s in the background while seamlessly browsing your site
Author: Rob Kay
Version: 0.7
Author URI: http://musicletloose.org
*/

global $mllp_plurl;
$mllp_plurl = plugin_dir_url(__FILE__);

include 'inc/mll-options.php';
include 'inc/mll-cpt.php';
include 'inc/mll-shortcodes.php';
include 'inc/mll-js-css-inc.php';
include 'inc/mll-controls.php';

	