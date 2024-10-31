<?php
function MLLPlayer_setupJukebox()
{
    $args_album = array(
        'post_type' => 'mllp_album',
        'posts_per_page' => '-1',
        'orderby' => 'meta_value_num',
        'meta_key' => 'order',
        'order' => 'ASC'
    );
    $args       = array(
        'post_type' => 'mllp_song',
        'posts_per_page' => '-1',
        'orderby' => 'meta_value_num',
        'meta_key' => 'track',
        'order' => 'ASC'
    );
    global $mllp_plurl;
?>
<!--[if IE]>
<div style="display:none;" id="IEhell">iehell</div> 
<![endif]-->
<script>

function MLLPlayer_mkim(url)
{
	return '<img src="'+url+'">';
}

jQuery(document).ready(function(){ 
       
var MLLPlayer_jukebox=new Array(); var i='';
var MLLPlayer_Ind=new Array(); var ind=0;
var MLLPlayer_Names=new Array();
var MLLPlayer_Artists=new Array();
soundManager.setup({

  // location: path to SWF files, as needed (SWF file name is appended later.)
debugMode: false,
    url: '<?php
    echo $mllp_plurl;
?>swf/',

  // optional: version of SM2 flash audio API to use (8 or 9; default is 8 if omitted, OK for most use cases.)
  // flashVersion: 9,

  // use soundmanager2-nodebug-jsmin.js, or disable debug mode (enabled by default) after development/testing
  // debugMode: false,

  // good to go: the onready() callback

  onready: function() {
<?php
    $album_query = new WP_Query($args_album);
    if ($album_query->have_posts()) {
        while ($album_query->have_posts()):
            $album_query->the_post();
             $this_album = $album_query->post->post_title;
            $my_query   = new WP_Query($args);
            
            if ($my_query->have_posts()) {
                while ($my_query->have_posts()):
                    $my_query->the_post();
                    if ($this_album == get_post_meta($my_query->post->ID, "album", true)) {
?>
    i='<?php
                        echo $my_query->post->ID;
?>';
    MLLPlayer_Ind[ind] = i; MLLPlayer_Names[ind]="<?php
                        echo $my_query->post->post_title;
?>"; MLLPlayer_Artists[ind]="<?php echo MLLPlayer_getTrackArtist($my_query->post->ID); ?>"; ind++;
    MLLPlayer_jukebox[i] = soundManager.createSound({id: 'a_'+'<?php
                        echo $my_query->post->ID;
?>', url: '<?php
                        echo get_post_meta($my_query->post->ID, "mpeg", true);
?>'});
  <?php
                    }
                endwhile;
            }
            
            wp_reset_postdata();
        endwhile;
    }
if(MLLPlayer_Sitewide())
{ ?>
pl=MLLPlayer_get_playlist();
MLLPlayer_update_player(MLLPlayer_Ind.indexOf(pl[0]));
<?php
} else {
	?>
	MLLPlayer_update_player(0);
	<?php } ?>
  
//jQuery('body').on("click",'.mllplayertrigger', function(e) { 
jQuery('.mllplayertrigger').live('click', function(e) {
        // some clicked play
e.preventDefault(); 
                                        var nme=jQuery(this).attr('name');
                              
                                        MLLPlayer_update_player(MLLPlayer_Ind.indexOf(nme));
                                    
                                        jQuery('.mllplayerpauser').each(function(){
           jQuery(this).removeClass('mllplayerpauser').addClass('mllplayertrigger');
jQuery(this).html(jQuery(this).data('mllplayer_plb')!='' ? MLLPlayer_mkim(jQuery(this).data('mllplayer_plb')) : '<?php
    $ph = MLLPlayer_Play();
    echo $ph;
?>');}); // make anything that's playing stop'
                                        soundManager.stopAll();
                                        MLLPlayer_play_recursive(nme);
                                        
                                        jQuery('a[name='+nme+']:not(".mllnoupdate")').each(function(){
	jQuery(this).addClass('mllplayerpauser').removeClass('mllplayertrigger');
 jQuery(this).html(
jQuery(this).data('mllplayer_stb')!='' ? MLLPlayer_mkim(jQuery(this).data('mllplayer_stb')) : '<?php
    $sh = MLLPlayer_Stop();
    echo $sh;
?>');});
                                        //make this play
     });
      jQuery('.mllplayerpauser').live("click", function(e) { e.preventDefault();
                                                    var nme=jQuery(this).attr('name');
                                                    MLLPlayer_jukebox[nme].stop();
                                                     jQuery('a[name='+nme+']:not(".mllnoupdate")').each(function(){jQuery(this).removeClass('mllplayerpauser').addClass('mllplayertrigger');
jQuery(this).html(jQuery(this).data('mllplayer_plb')!='' ? MLLPlayer_mkim(jQuery(this).data('mllplayer_plb')) : '<?php
    $ph = MLLPlayer_Play();
    echo $ph;
?>'); });
  });
    jQuery('.stopmusic').live('click', function() {soundManager.stopAll();}); 
}});

function MLLPlayer_play_recursive(nme)
{
	MLLPlayer_jukebox[nme].play(

<?php if(MLLPlayer_FollowOn()){?>{onfinish: function() {
                                        
jQuery('.mllplayerpauser').each(function(){jQuery(this).removeClass('mllplayerpauser').addClass('mllplayertrigger');
jQuery(this).html(jQuery(this).data('mllplayer_plb')!='' ? MLLPlayer_mkim(jQuery(this).data('mllplayer_plb')) : '<?php
    $ph = MLLPlayer_Play();
    echo $ph;
?>');});

var next;

<?php if(!MLLPlayer_Sitewide()) { ?>
next=MLLPlayer_next_sitewide(nme);
if(next=='')
{
 next=MLLPlayer_next_page(nme);
 }
<?php } else { ?>
 next=MLLPlayer_next_page(nme);
// alert('hello'+next['item']+'m'+next['index']);
 if(next=='')
 { next=MLLPlayer_next_sitewide(nme); }
<?php } ?>
//alert('heree'+next[item]+' '+next[index]);
pl=MLLPlayer_get_playlist();
if(next=='')
{
MLLPlayer_update_player(MLLPlayer_Ind.indexOf(pl[0]));

MLLPlayer_update_player(0);

}
                    else{          // take everything off play
                                               
                                        	MLLPlayer_update_player(next['index']);
                                        	
                                        	jQuery('a[name='+next['item']+']:not(".mllnoupdate")').each(function(){
                                        		jQuery(this).html(jQuery(this).data('mllplayer_stb')!='' ? MLLPlayer_mkim(jQuery(this).data('mllplayer_stb')) : '<?php
    $ph = MLLPlayer_Stop();
    echo $ph;
?>');
jQuery(this).removeClass('mllplayertrigger').addClass('mllplayerpauser');});

                                        	MLLPlayer_play_recursive(next['item']);
                                        }}
                                        
                                        }<?php } ?>);
                                   
}


function MLLPlayer_get_playlist()
{
	return [<?php $pl=MLLPlayer_Playlist(); $f=true; foreach($pl as $k=>$v) { echo ($f ? '' : ',').'"'.$v.'"'; $f=false; }?>];
}

function MLLPlayer_next_sitewide(nme)
{ // returns next if sitewide or ''
playlist=MLLPlayer_get_playlist();
index = playlist.indexOf(nme.toString());
//alert('this'+MLLPlayer_Ind.indexOf(playlist[index+1]));
if(index==-1) return ''; if(index >= 0 && index < playlist.length - 1) {nextItem = playlist[index+1]; ind=MLLPlayer_Ind.indexOf(playlist[index+1]); return {index: ind, item:nextItem };  } else { 
//<php if(MLLPlayer_Repeat()) { > 
//nextItem=playlist[0]; ind=0;
//<php } else { >
return '';
//<php } > 

}

return {index: ind, item:nextItem }; 
}

function MLLPlayer_next_page(nme)
{ // returns next on page or ''
starthere=0;
item='';
items=jQuery('<?php echo MLLPlayer_ID(); ?> a.mllplayertrigger').
each(function(index, value) {
if(starthere==1)
{
 item=value.getAttribute('name');
 starthere=2;
}
if(value.getAttribute('name')==nme)
{
starthere=1;
}
}); 
if(item=='')
 return '';
ind=MLLPlayer_Ind.indexOf(item);
if(ind==-1)
return '';
else
return {index: ind, item:item }; 
}

function MLLPlayer_update_player(index)
{
jQuery(document).find('.mllplayer_curtrack').html(MLLPlayer_Names[index]);
jQuery(document).find('.mllplayer_curartist').html(MLLPlayer_Artists[index]);
	jQuery(document).find('.updateme > a').attr('name', MLLPlayer_Ind[index]);
	playlist=MLLPlayer_get_playlist();
	plind=playlist.indexOf(MLLPlayer_Ind[index]);
	if(plind>0 && plind!=-1) prevItem = plind-1; else prevItem=playlist.indexOf(MLLPlayer_Ind[index]);
	if(plind<playlist.length-1 && plind!=-1) nextItem=plind+1; else {
	nextItem=playlist.indexOf(MLLPlayer_Ind[index]);
		}
	//alert('plind: '+plind+' index: '+index + ' next: ' +nextItem+', '+MLLPlayer_Names[MLLPlayer_Ind.indexOf(playlist[nextItem])]+' prev: '+MLLPlayer_Names[MLLPlayer_Ind.indexOf(playlist[prevItem])]);
	jQuery(document).find('.mllplayer_fwdbut > a').attr('name', playlist[nextItem]);
	jQuery(document).find('.mllplayer_bckbut > a').attr('name', playlist[prevItem]);
}
	
function MLLPlayer_Ajax_Load(url){
	jQuery.get(url, function(resp) {
	jQuery($mllplayermenuid).html(jQuery(resp).find($mllplayermenuid).html());
	
    	
    	jQuery($mllplayerdivid).html(jQuery(resp).find($mllplayerdivid).html());
    	
    	jQuery('.mllplayertrigger').each(function(e) {
                                        var nme=jQuery(this).attr('name');
                                        if(jQuery.inArray(nme, MLLPlayer_jukebox) != -1) {if(MLLPlayer_jukebox[nme].playState==1) {
                                        jQuery(this).addClass('mllplayerpauser');
                                        jQuery(this).removeClass('mllplayertrigger');
                                        jQuery(this).html(this.data('mllplayer_stb')!='' ? MLLPlayer_mkim(this.data('mllplayer_stb')) : '<?php
    $ph = MLLPlayer_Stop();
    echo $ph;
?>'); }}
     });
  jQuery($mllplayerdivid).animate({opacity: "1"})
		})
}


	
<?php
    $mllplayerajax = MLLPlayer_Ajax();
    if ($mllplayerajax == 'true') {
?>
var $mllplayerdivid = '<?php
        $divid = MLLPlayer_ID();
        echo $divid;
?>';
var $mllplayermenuid = '<?php
        $menuid = MLLPlayer_Menu();
        echo $menuid;
?>';
   var siteUrl = "http://" + top.location.host.toString(),
		url = '';
		
		window.onpopstate=function(){
	var State = history.state;
var theurl = State.path;
MLLPlayer_Ajax_Load(theurl);
	};
	
	jQuery(document).on("click", "a[href^='"+siteUrl+"']:not([href*='/wp-admin/']):not([href*='/wp-login.php']):not([href$='/feed/']):not([href*='.zip']):not([href*='.png']):not([href*='.jpg']):not([href*='.pdf'])", function() {
	
//location.hash = this.pathname;
var so={path: siteUrl+this.pathname};
history.pushState(so,'',siteUrl+this.pathname);
	jQuery($mllplayerdivid).animate({opacity: "0.1"}).html('<?php
        $mllplayerload = MLLPlayer_Fade();
        echo $mllplayerload;
?>'); MLLPlayer_Ajax_Load(siteUrl+this.pathname);
return false;
});
    
<?php
    }
?>
});
</script>
<?php
}

add_action('wp_footer', 'MLLPlayer_setupJukebox');
?>