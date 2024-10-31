<?php
// Link the sm2 scripts

function MLLPlayer_scripts()
{
	global $mllp_plurl;
  	wp_enqueue_script( 'soundmanager2', $mllp_plurl.'js/soundmanager2.js');
}
add_action( 'wp_enqueue_scripts', 'MLLPlayer_scripts', 5 );

function MLLPlayer_styles()
{
	global $mllp_plurl;
  	$cs = get_option('mllplayer_options');
    if ($cs['styleurl'] == "")
    wp_enqueue_style( 'mllp_style', $mllp_plurl. 'mll_style.css');
    else
    wp_enqueue_style( 'mllp_style', $cs['styleurl']);
}
add_action( 'wp_enqueue_scripts', 'MLLPlayer_styles' );

?>