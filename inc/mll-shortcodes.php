<?php

function mllplayer_jq()
{
    if (!is_admin()) {
        wp_enqueue_script('jquery');
    }
}
add_action('wp_enqueue_scripts', 'mllplayer_jq');

// output data to front end

function MLLPlayer_getTrackArtist($id)
{
	$trackartist=get_post_meta($id, 'track_artist', true);
	return $trackartist;
}

function MLLPlayer_getArtists()
{
    $args     = array(
        'post_type' => 'mllp_artist',
        'orderby' => 'rand',
        'posts_per_page' => '-1'
    );
    $my_query = new WP_Query($args);
    if ($my_query->have_posts()) {
?>
    <ul style="list-style-type: none; margin-bottom: 10px;">
    <?php
        while ($my_query->have_posts()):
            $my_query->the_post();
            $img_details = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
?>
    <li style="clear:left; padding-top: 5px;">
      <img src="<?php
            echo $img_details[0];
?>" align="left" style="padding-right: 10px; padding-bottom: 0px;" width="<?php
            echo $img_details[1];
?>" height="<?php
            echo $img_details[2];
?>" /><a href="<?php
            echo get_permalink($post->ID);
?> "><?php
            the_title();
?></a><?php
            the_excerpt();
?> 
    </li> 
    <?php
        endwhile;
?> 
    </ul> 
    <?php
    }
}

function MLLPlayer_shortcode_getArtists($atts)
{
    ob_start();
    MLLPlayer_getArtists();
    $ietc = ob_get_contents();
    ob_end_clean();
    return $ietc;
}

add_shortcode('mllp_getartists', 'MLLPlayer_shortcode_getArtists');

function MLLPlayer_getAlbums($artist, $artistheader)
{
    global $wpdb;
    
    $Q = "SELECT post_id, meta_value FROM " . $wpdb->base_prefix . "postmeta WHERE meta_key = 'order'";
    $Q .= " AND (post_id IN (SELECT post_id FROM " . $wpdb->base_prefix . "postmeta WHERE meta_key='artist'";
    $Q .= " AND meta_value='" . $artist . "')) ORDER BY meta_value";
    
    $myalbums = $wpdb->get_results($Q);
    if ($artistheader == true) {
?>
<h1><?php
        echo $artist;
?></h1>
<?php
        $args = array(
            'post_type' => 'mllp_artist',
            'name' => $artist
        );
        
        $my_query = new WP_Query($args);
        if ($my_query->have_posts()) {
            while ($my_query->have_posts()):
                $my_query->the_post();
                $img_details = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
?>
<img src="<?php
                echo $img_details[0];
?>" align="left" style="padding-right: 10px; padding-bottom: 0px;" width="<?php
                echo $img_details[1];
?>" height="<?php
                echo $img_details[2];
?>" /><?php
                the_content();
            endwhile;
        }
        echo '<br clear="both"/><br/>';
    }
    foreach ($myalbums as $album):
        $albumpost = get_post($album->post_id);
        getSongs($albumpost->post_title, false);
    endforeach;
}

function MLLPlayer_shortcode_getAlbums($atts)
{
    extract(shortcode_atts(array(
        'artist' => '',
        'header' => 'false'
    ), $atts));
    ob_start();
    MLLPlayer_getAlbums($artist, $header);
    $ietc = ob_get_contents();
    ob_end_clean();
    return $ietc;
}

add_shortcode('mllp_getalbums', 'MLLPlayer_shortcode_getAlbums');

function getSongs($album, $lyricslink)
{
    
    global $wpdb;
    
    $Q = "SELECT post_id, meta_value FROM " . $wpdb->base_prefix . "postmeta WHERE meta_key = 'track'";
    $Q .= " AND (post_id IN (SELECT post_id FROM " . $wpdb->base_prefix . "postmeta WHERE meta_key='album'";
    $Q .= " AND meta_value='" . $album . "')) ORDER BY meta_value";
    
    $mysongs = $wpdb->get_results($Q);
    
?>

<?php
    $args = array(
        'post_type' => 'mllp_album',
        'name' => $album
    );
    
    $my_query = new WP_Query($args);
    if ($my_query->have_posts()) {
?>
<h3><?php
        echo $album;
        if (get_post_meta($my_query->post->ID, 'available', true) == 'dl') {
?>
      <a href="<?php
            echo get_post_meta($my_query->post->ID, 'file', true);
?>"><?php
            echo MLLPlayer_Dl();
?></a>
<?php
        }
?></h3>
<?php
        while ($my_query->have_posts()):
?>
<?php
            $my_query->the_post();
?>
<?php
            $img_details = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
?>
<img src="<?php
            echo $img_details[0];
?>" align="left" style="padding-right: 10px; padding-bottom: 0px;" width="<?php
            echo $img_details[1];
?>" height="<?php
            echo $img_details[2];
?>" /><?php
        endwhile;
    }
?>
<table class="track-listing">
<?php
    foreach ($mysongs as $song):
        $songpost = get_post($song->post_id);
?>
  <tr>
<td class="track-num"><?php
        echo get_post_meta($song->post_id, 'track', true);
?></td>
<td class="track-name"><?php
        echo $songpost->post_title;
?></td>
<td class="track-listen"><?php echo MLLPlayer_PlayBut($song->post_id); ?></td>
<?php
        if (get_post_meta($song->post_id, 'available', true) == 'dl') {
?>
      <td class="track-download">     <a href="<?php
            echo get_post_meta($song->post_id, 'mpeg', true);
?>"><?php
            $dl = MLLPlayer_Dl();
            echo $dl;
?></a></td>
<?php
        }
?>    
</tr>
<?php
    endforeach;
?>
</table><br clear="left" />&nbsp;<br />
<?php
}

function shortcode_mllplayerlink()
{
    return 'Music Player Powered by <a href="http://musicletloose.org" target="_blank">MLLPlayer</a>.';
}

add_shortcode('mllp_link', 'shortcode_mllplayerlink');

function MLLPlayer_Player()
{
    return "<div class='mllplayer_playbut updateme'><a href='' data-mllplayer_plb='' data-mllplayer_stb='' class='mllplayertrigger' name=''>" . MLLPlayer_Play() . "</a></div>";
}

add_shortcode('mllp_playcurtrack', 'MLLPlayer_Player');

function MLLPlayer_CurTrack()
{
    return "<div class='mllplayer_curtrack'></div>";
}
add_shortcode('mllp_curtrack', 'MLLPlayer_CurTrack');

function MLLPlayer_CurArtist()
{
    return "<div class='mllplayer_curartist'></div>";
}
add_shortcode('mllp_curartist', 'MLLPlayer_CurArtist');

function MLLPlayer_PlayBut($id, $customplay='', $customstop='')
{
	$ret='<span class="mllplayer_playbut"><a href="" class="mllplayertrigger"';
	$ret.=' data-mllplayer_plb="'.$customplay.'"';
	$ret.=' data-mllplayer_stb="'.$customstop.'"';
	$ret.=' name="'.$id.'">';
 $ph = MLLPlayer_Play();
 if($customplay=='') $ret.=$ph; else $ret=$ret.'<img src="'.$customplay.'">';
 $ret.='</a><span>';
return $ret;
}
function shortcode_MLLPlayer_PlayBut($atts)
{
extract(shortcode_atts(array('id' => '', 'customplay' => '', 'customstop' => ''), $atts));
return MLLPlayer_PlayBut($id, $customplay, $customstop);
}

add_shortcode('mllp_play', 'shortcode_MLLPlayer_PlayBut');

function MLLPlayer_FwdBut($id, $customfwd='')
{
	$ret='<div class="mllplayer_fwdbut"><a href="" class="mllplayertrigger mllnoupdate"';
	$ret.=' data-mllplayer_fwd="'.$customfwd.'"';
	$ret.=' name="'.$id.'">';
 $ph = MLLPlayer_Fwd();
 if($customfwd=='') $ret.=$ph; else $ret=$ret.'<img src="'.$customfwd.'">';
 $ret.='</a></div>';
return $ret;
}
function shortcode_MLLPlayer_FwdBut($atts)
{
extract(shortcode_atts(array('customfwd' => ''), $atts));
return MLLPlayer_FwdBut('', $customfwd);
}

add_shortcode('mllp_fwd', 'shortcode_MLLPlayer_FwdBut');

function MLLPlayer_BckBut($id, $custombck='')
{
	$ret='<div class="mllplayer_bckbut"><a href="" class="mllplayertrigger mllnoupdate"';
	$ret.=' data-mllplayer_bck="'.$custombck.'"';
	$ret.=' name="'.$id.'">';
 $ph = MLLPlayer_Bck();
 if($custombck=='') $ret.=$ph; else $ret=$ret.'<img src="'.$custombck.'">';
 $ret.='</a></div>';
return $ret;
}
function shortcode_MLLPlayer_BckBut($atts)
{
extract(shortcode_atts(array('custombck' => ''), $atts));
return MLLPlayer_BckBut('', $custombck);
}

add_shortcode('mllp_bck', 'shortcode_MLLPlayer_BckBut');


// Creating the widget
class mllp_widget extends WP_Widget {
function __construct() {
parent::__construct(
// Base ID of your widget
'mllp_widget',
// Widget name will appear in UI
__('MLL Player Widget', 'mllp_widget_domain'),
//Widget description
array( 'description' => __( 'MLL Player widget', 'mllp_widget_domain' ), )
);
}
	 
// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
global $mllp_plurl;
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
// This is where you run the code and display the output
echo do_shortcode("<div class='mllp_widget'>[mllp_bck custombck='".$mllp_plurl.'images/bck.png'."'][mllp_playcurtrack customplay='".$mllp_plurl.'images/play.png'."' customstop='".$mllp_plurl.'images/pause.png'."'][mllp_fwd customfwd='".$mllp_plurl.'images/fwd.png'."']<div class='mllplayer_plinfo'>[mllp_curtrack][mllp_curartist]</div></div>");
echo $args['after_widget'];
}
// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'mllp_widget_domain' );
}
	// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
}
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class ml_widget ends here
	
// Register and load the widget
function mllp_load_widget() {
	register_widget( 'mllp_widget' );
}
add_action( 'widgets_init', 'mllp_load_widget' );