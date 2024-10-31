<?php // Admin options

// add the admin options page
add_action('admin_menu', 'MLLPlayer_admin_add_page');
function MLLPlayer_admin_add_page() {
add_options_page('MLL Player Settings', 'MLL Player', 'manage_options', 'MLLPlayer', 'MLLPlayer_options_page');
}

// display the admin options page
function MLLPlayer_options_page() {
?>
<div id='MLLPlayer'>
<h2>MLL Player Settings</h2>


<form action="options.php" method="post">
<?php
settings_fields('MLLPlayer_options'); 
 do_settings_sections('MLLPlayersettings');?>
 <h3>Playlist</h3>
  <?php MLLPlayer_playlist_fn(); ?><br clear="both"><br>
<input name="MLLPlayer_postreset" style='display:none;' type="text" value="false" />
<input name="MLLPlayer_submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
<input type='button' onclick='this.form.MLLPlayer_postreset.value="true"; this.form.submit();' value='Reset' name="MLLPlayer_reset" />
</form></div>
 
<?php
}
// add the admin settings and such
add_action('admin_init', 'MLLPlayer_admin_init');

function MLLPlayer_admin_init(){
	if(isset($_POST['MLLPlayer_postreset'])) {
 if($_POST['MLLPlayer_postreset']=='true') { $_POST['MLLPlayer_options']= MLLPlayer_getdefaults() ; MLLPlayer_setdefaults(); }}
register_setting( 'MLLPlayer_options', 'MLLPlayer_options', 'MLLPlayer_options_validate' );
register_setting( 'MLLPlayer_playlist', 'MLLPlayer_playlist' );
add_settings_section('MLLPlayer_ajax', 'AJAX Options', 'MLLPlayer_ajax_text', 'MLLPlayersettings', 'MLLPlayer_ajax');
add_settings_field('MLLPlayer_ajaxonoff', 'AJAX On or Off', 'MLLPlayer_ajaxonoff_fn', 'MLLPlayersettings', 'MLLPlayer_ajax');
add_settings_field('MLLPlayer_ajaxdomid', 'ID of AJAX DOM element', 'MLLPlayer_domid_fn', 'MLLPlayersettings', 'MLLPlayer_ajax');
add_settings_field('MLLPlayer_ajaxmenuid', 'ID of Menu element', 'MLLPlayer_menuid_fn', 'MLLPlayersettings', 'MLLPlayer_ajax');
add_settings_section('MLLPlayer_display', 'Display Options', 'MLLPlayer_display_text', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_field('MLLPlayer_fade', 'Loading fade html', 'MLLPlayer_fade_fn', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_field('MLLPlayer_play', 'Play button image url', 'MLLPlayer_play_fn', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_field('MLLPlayer_fwd', 'fwd button image url', 'MLLPlayer_fwd_fn', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_field('MLLPlayer_bck', 'back button image url', 'MLLPlayer_bck_fn', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_field('MLLPlayer_stop', 'Stop button image url', 'MLLPlayer_stop_fn', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_field('MLLPlayer_dl', 'Download button html', 'MLLPlayer_dl_fn', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_field('MLLPlayer_styleurl', 'Custom Stylesheet Url', 'MLLPlayer_styleurl_fn', 'MLLPlayersettings', 'MLLPlayer_display');
add_settings_section('MLLPlayer_player', 'Player Options', 'MLLPlayer_player_text', 'MLLPlayersettings', 'MLLPlayer_player');
add_settings_field('MLLPlayer_followon', 'Follow On Play', 'MLLPlayer_followon_fn', 'MLLPlayersettings', 'MLLPlayer_player');
//add_settings_field('MLLPlayer_repeat', 'Repeat Play', 'MLLPlayer_repeat_fn', 'MLLPlayersettings', 'MLLPlayer_player');
//	add_settings_field('MLLPlayer_sitewide', 'Prioritise Page Playlist Over Sitewide For Follow on', 'MLLPlayer_sitewide_fn', 'MLLPlayersettings', 'MLLPlayer_player');
//add_settings_field('MLLPlayer_playlist', 'Playlist', 'MLLPlayer_playlist_fn', 'MLLPlayersettings', 'MLLPlayer_player');
}

function MLLPlayer_ajaxonoff_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_ajaxonoff' name='MLLPlayer_options[ajaxonoff]' size='40' type='text' value='{$options['ajaxonoff']}' />";
}
function MLLPlayer_domid_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_domid' name='MLLPlayer_options[domid]' size='40' type='text' value='{$options['domid']}' />";
}
function MLLPlayer_menuid_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_menuid' name='MLLPlayer_options[menuid]' size='40' type='text' value='{$options['menuid']}' />";
}
function MLLPlayer_fade_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_fade' name='MLLPlayer_options[fade]' size='40' type='text' value='{$options['fade']}' />";
}
function MLLPlayer_play_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_play' name='MLLPlayer_options[play]' size='40' type='text' value='{$options['play']}' />";
}
function MLLPlayer_stop_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_stop' name='MLLPlayer_options[stop]' size='40' type='text' value='{$options['stop']}' />";
}
function MLLPlayer_fwd_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_stop' name='MLLPlayer_options[fwd]' size='40' type='text' value='{$options['fwd']}' />";
}
function MLLPlayer_bck_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_bck' name='MLLPlayer_options[bck]' size='40' type='text' value='{$options['bck']}' />";
}

function MLLPlayer_dl_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_dl' name='MLLPlayer_options[dl]' size='40' type='text' value='{$options['dl']}' />";
}
function MLLPlayer_styleurl_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_styleurl' name='MLLPlayer_options[styleurl]' size='40' type='text' value='{$options['styleurl']}' />";
}
function MLLPlayer_followon_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_followon' name='MLLPlayer_options[followon]' size='40' type='text' value='{$options['followon']}' />";
}
function MLLPlayer_repeat_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_repeat' name='MLLPlayer_options[repeat]' size='40' type='text' value='{$options['repeat']}' />";
}
function MLLPlayer_sitewide_fn() {
$options = get_option('MLLPlayer_options');
echo "<input id='MLLPlayer_sitewide' name='MLLPlayer_options[sitewide]' size='40' type='text' value='{$options['sitewide']}' />";
}

function MLLPlayer_playlist_fn() {
$options = get_option('MLLPlayer_playlist');
echo '<ul style="float:left;" id="MLLPlayer_playlist">';
$playlist=explode(',',$options['playlist']);
foreach($playlist as $item)
{ if($item!=''){ ?> <li id="<?php echo $item; ?>" class="mllplitem"><?php echo get_the_title($item); ?>--<a href="#" class="mllplup">up</a>--<a href="#" class="mllpldn">down</a>--<a href="#" class="mllplre">remove</a></li>
 <?php } }
echo '
</ul>'; ?>
<br clear='both'>
<select class='mllplse'><?php
global $post;
    $tmp_post = $post;
    $myposts  = get_posts('post_type=mllp_song&numberposts=-1');
    $values   = array(
        ''
    );
    foreach ($myposts as $post):
        $obj=array('idd' => get_the_id(), 'title' => get_the_title($post->id));
        array_push($values, $obj);
    endforeach;
    $post = $tmp_post;
    unset($values[0]);
    foreach ($values as $value):
?>
                <option value="<?php
        echo $value['idd'];
?>" name="<?php
        echo $value['idd'];
?>"><?php
        echo $value['title'];
?></option>
            <?php
    endforeach;
?>  
</select>
<a href='#' class='mllplad'>Add to Playlist</a>
<?php
}

function MLLPlayer_ajax_text() {
echo '<p>These options relate to the ajax behaviour of the plugin (ie how it loads page data without having to reload the webpage).</p>';
}
function MLLPlayer_display_text() {
echo '<p>These options relate to the display of your music/player on the front end of the site</p>';
}
function MLLPlayer_player_text() {
echo '<p>These options relate to the functionality of your player and site-wide playlist</p>';
}

function MLLPlayer_options_validate($input) {
return $input;
}

//Follow on play
//Repeat
//Use playlist

/* What to do when the plugin is activated? */
register_activation_hook(__FILE__,'MLLPlayer_install');

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'MLLPlayer_remove' );

function MLLPlayer_install()
{
	$defaults=MLLPlayer_getdefaults();
    // Check to see if the option exists
    $retrieved_options = get_option( 'MLLPlayer_options' ) ;

    if ( $retrieved_options == '' ) {
        // There are no options set
        add_option( 'MLLPlayer_options', $defaults );
    } elseif ( count( $retrieved_options ) == 0 ) {
        // All options are blank
        update_option( 'MLLPlayer_options', $defaults  );
    }
    	$defaults=MLLPlayer_getdefaultplaylist();
    // Check to see if the option exists
    $retrieved_options = get_option( 'MLLPlayer_playlist' ) ;

    if ( $retrieved_options == '' ) {
        // There are no options set
        add_option( 'MLLPlayer_playlist', $defaults );
    } elseif ( count( $retrieved_options ) == 0 ) {
        // All options are blank
        update_option( 'MLLPlayer_playlist', $defaults  );
    }
}

function MLLPlayer_remove()
{
	delete_option('MLLPlayer_options');
}

function MLLPlayer_setdefaults()
{
	$options = MLLPlayer_getdefaults();
	update_option('MLLPlayer_options',$options);
}

function MLLPlayer_getdefaults()
{
$options=array('ajaxonoff'=>'false','domid'=>'#content', 'menuid'=>'#navbar', 'fade'=>'<p>Loading, please wait...</p>', 'play'=>'', 'stop'=>'', 'fwd'=>'', 'bck'=>'', 'dl'=>'', 'playlist'=>'', 'followon'=>'false', 'repeat'=>'false', 'sitewide'=>'false');
	return $options;
}

function MLLPlayer_getdefaultplaylist()
{
$options=array('playlist'=>'');
	return $options;
}

function MLLPlayer_update_playlist(){
	$options = get_option( 'MLLPlayer_playlist' );
 	$options['playlist']=$_POST['playlist'];
update_option('MLLPlayer_playlist',$options);
}
add_action('wp_ajax_MLLPlayer_update_playlist', 'MLLPlayer_update_playlist' );

function MLLPlayeradminjs()
{?>
<script type='text/javascript'>
/* <![CDATA[ */
function MLLPlayer_update_playlist_ajax()
{
	var pl='';
	//alert('here: ');
jQuery('.mllplitem').each(function(){pl=pl+jQuery(this).attr('id')+',';});
pl=pl.substring(0, pl.length - 1);
jQuery.post('<?php echo admin_url( 'admin-ajax.php' ); ?>', {'action' : 'MLLPlayer_update_playlist', 'playlist' : pl });
}

jQuery(document).ready(function(){
jQuery( document ).on( "click", ".mllplup", function(e){
	e.preventDefault(); 

if(!(jQuery(this).parent().is(':first-child'))) {
jQuery(jQuery(this).parent()).insertBefore(jQuery(jQuery(this).parent()).prev());
MLLPlayer_update_playlist_ajax();
}
});
jQuery( document ).on( "click", ".mllpldn", function(e){
	e.preventDefault(); 

if(!(jQuery(this).parent().is(':last-child'))) {
jQuery(jQuery(this).parent()).insertAfter(jQuery(jQuery(this).parent()).next());
MLLPlayer_update_playlist_ajax();
}
});
jQuery( document ).on( "click", ".mllplad", function(e){
	e.preventDefault(); 
	var seltit=jQuery('.mllplse option:selected').text();
	var sel=jQuery('.mllplse option:selected').attr('name');

var li='<li style="float:none;" id="'+sel+'" class="mllplitem">'+seltit+'--<a href="#" class="mllplup">up</a>--<a href="#" class="mllpldn">down</a>--<a href="#" class="mllplre">remove</a></li>';
jQuery(jQuery('#MLLPlayer_playlist')).append(li);
MLLPlayer_update_playlist_ajax();
});
jQuery( document ).on( "click", ".mllplre", function(e){
	e.preventDefault(); 
jQuery(jQuery(this).parent()).remove();
MLLPlayer_update_playlist_ajax();
});
});
/* ]]> */
</script><?php
}
add_action('admin_footer', 'MLLPlayeradminjs');

function MLLPlayer_flusher()
{
    // Register code for your new post type here...
    // register_post_type( 'custom_post_type_name', $customPostTypeDefs );
    
    // Check the option we set on activation.
    if (get_option('MLLPlayer_flush') == 'true') {
        flush_rewrite_rules();
        delete_option('MLLPlayer_flush');
    }
}

add_action('init', 'MLLPlayer_flusher', 100);


//wrapper for options

function MLLPlayer_Ajax()
{
    $MLLPlayerajax = get_option('MLLPlayer_options');
    if ($MLLPlayerajax['ajaxonoff'] == '')
        return 'false';
    else
        return $MLLPlayerajax['ajaxonoff'];
}
function MLLPlayer_Fade()
{
    $MLLPlayerfade = get_option('MLLPlayer_options');
    if ($MLLPlayerfade['fade'] == '')
        return '';
    else
        return $MLLPlayerfade['fade'];
}
function MLLPlayer_ID()
{
    $MLLPlayerid = get_option('MLLPlayer_options');
    if ($MLLPlayerid['domid'] == '')
        return '#content';
    else
        return $MLLPlayerid['domid'];
}
function MLLPlayer_Menu()
{
    $MLLPlayerid = get_option('MLLPlayer_options');
    if ($MLLPlayerid['menuid'] == '')
        return '#navbar';
    else
        return $MLLPlayerid['menuid'];
}
function MLLPlayer_Play()
{
	global $mllp_plurl;
    $MLLPlayerplay = get_option('MLLPlayer_options');
    if ($MLLPlayerplay['play'] == '')
        return '<img src="'.$mllp_plurl.'/images/play.png'.'">';
    else
        return '<img src="'.$MLLPlayerplay['play'].'">';
}
function MLLPlayer_Bck()
{
		global $mllp_plurl;
   $MLLPlayerplay = get_option('MLLPlayer_options');
    if ($MLLPlayerplay['bck'] == '')
        return '<img src="'.$mllp_plurl.'/images/bck.png'.'">';
    else
        return '<img src="'.$MLLPlayerplay['bck'].'">';
}
function MLLPlayer_Fwd()
{
		global $mllp_plurl;
    $MLLPlayerplay = get_option('MLLPlayer_options');
    if ($MLLPlayerplay['fwd'] == '')
        return '<img src="'.$mllp_plurl.'/images/fwd.png'.'">';
   else
        return '<img src="'.$MLLPlayerplay['fwd'].'">';
}
function MLLPlayer_Stop()
{
		global $mllp_plurl;
    $MLLPlayerstop = get_option('MLLPlayer_options');
    if ($MLLPlayerstop['stop'] == '')
        return '<img src="'.$mllp_plurl.'/images/pause.png'.'">';
    else
        return '<img src="'.$MLLPlayerstop['stop'].'">';
}
function MLLPlayer_Dl()
{
    $MLLPlayerdl = get_option('MLLPlayer_options');
    if ($MLLPlayerdl['dl'] == '')
        return 'Download';
    else
        return '<img src="'.$MLLPlayerdl['dl'].'">';
}
function MLLPlayer_FollowOn()
{
	$options = get_option('MLLPlayer_options');
	if($options['followon']=='true')
	return true;
	else
	return false;
}
function MLLPlayer_Repeat()
{
	$options = get_option('MLLPlayer_options');
	if($options['repeat']=='true')
	return true;
	else
	return false;
}
function MLLPlayer_Sitewide()
{
//	$options = get_option('MLLPlayer_options');
	//if($options['sitewide']=='true')
	return true;
//	else
//return false;
}
function MLLPlayer_Playlist()
{
	$options = get_option('MLLPlayer_playlist');
	$pl=explode(',',$options['playlist']);
	return $pl;
}

?>