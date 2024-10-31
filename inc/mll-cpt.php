<?php
// custom post types

// Create the 'song' post type

function MLLPlayer_post_type_song()
{
    register_post_type('mllp_song', array(
        'label' => 'Songs',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array(
            'slug' => 'music/songs',
            'with_front' => false
        ),
        'query_var' => true,
        'supports' => array(
            'title',
            'comments',
            'editor'
        )
    ));
}

// Columns for list view

function MLLPlayer_song_edit_columns($columns)
{
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Title",
        "album" => "Album"
    );
    
    return $columns;
}

// Add a meta box to the edit screen

function MLLPlayer_song_meta_box_add()
{
    add_meta_box('mllp_song-meta-box-id', 'Song Details', 'MLLPlayer_song_meta_box_render', 'mllp_song', 'normal', 'high');
}

// Render our meta-box

function MLLPlayer_song_meta_box_render($post)
{
    $songvalues = get_post_custom($post->ID);
    $album      = isset($songvalues['album']) ? esc_attr($songvalues['album'][0]) : '';
    $track_artist        = isset($songvalues['track_artist']) ? esc_attr($songvalues['track_artist'][0]) : '';
    $track      = isset($songvalues['track']) ? esc_attr($songvalues['track'][0]) : '';
    $mpeg       = isset($songvalues['mpeg']) ? esc_attr($songvalues['mpeg'][0]) : ''; // streaming file
    $available  = isset($songvalues['available']) ? esc_attr($songvalues['available'][0]) : '';
    wp_nonce_field('mllp_song_meta_box_nonce', 'meta_box_nonce');
?> 
        <p>  
        <label for="track">Track</label>
        <input type="text" name="track" id="track" value="<?php
    echo $track;
?>" />
            <br>
       
            <label for="album">Album</label>  
            <select name="album" id="album">
            <?php // get an array of all the albums and make them into options available to select
    global $post;
    $tmp_post = $post;
    $myposts  = get_posts('post_type=mllp_album&numberposts=-1');
    $values   = array(
        ''
    );
    foreach ($myposts as $post):
        array_push($values, get_the_title($post->id));
    endforeach;
    $post = $tmp_post;
    unset($values[0]);
    foreach ($values as $value):
?>
                <option value="<?php
        echo $value;
?>" <?php
        selected($album, $value);h
?>><?php
        echo $value;
?></option>
            <?php
    endforeach;
?>  
            </select>
         
            <br>
                  <label for="file">Track Artist</label>
        <input type="text" name="track_artist" id="track_artist" value="<?php
    echo $track_artist;
?>" /><br>
            <label for="mpeg">MP3</label>
            <input type="text" name="mpeg" id="mpeg" value="<?php
    echo $mpeg;
?>" />
            <br>
            <label for="available">Available As</label>
            <select name="available" id="available">
            <option value="nodl" <?php
    selected($available, 'nodl');
?>>Streaming Only</option>
            <option value="dl" <?php
    selected($available, 'dl');
?>>Download</option>
            </select>
       </p>  
        <?php
}

// Save our data to mysql
function MLLPlayer_song_meta_box_save($post_id)
{
    // Bail if we're doing an auto save  
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    
    // if our nonce isn't there, or we can't verify it, bail 
    if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'mllp_song_meta_box_nonce'))
        return;
    
    // if our current user can't edit this post, bail  
    if (!current_user_can('edit_post'))
        return;
    
    // Make sure your data is set before trying to save it  
    
    if (isset($_POST['track']))
        update_post_meta($post_id, 'track', esc_attr($_POST['track']));
        if (isset($_POST['track_artist']))
        update_post_meta($post_id, 'track_artist', esc_attr($_POST['track_artist']));
    if (isset($_POST['mpeg']))
        update_post_meta($post_id, 'mpeg', esc_attr($_POST['mpeg']));
    if (isset($_POST['album']))
        update_post_meta($post_id, 'album', esc_attr($_POST['album']));
    if (isset($_POST['available']))
        update_post_meta($post_id, 'available', esc_attr($_POST['available']));
    
}

// display album in list view
function MLLPlayer_song_custom_columns($column)
{
    switch ($column) {
        case "album":
            $custom = get_post_custom();
            echo $custom["album"][0];
            break;
    }
}

// Make it all happen
add_action('init', 'MLLPlayer_post_type_song');
add_filter("manage_edit-song_columns", "MLLPlayer_song_edit_columns");
add_action("manage_posts_custom_column", "MLLPlayer_song_custom_columns");
add_action('save_post', 'MLLPlayer_song_meta_box_save');
add_action('add_meta_boxes', 'MLLPlayer_song_meta_box_add');

// Album post type, edit columns

function MLLPlayer_post_type_album()
{
    // Creates album post type
    register_post_type('mllp_album', array(
        'label' => 'Albums',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array(
            'slug' => 'music/albums',
            'with_front' => false
        ),
        'query_var' => true,
        'supports' => array(
            'title',
            'thumbnail',
            'comments',
            'editor'
        )
    ));
}

function MLLPlayer_album_meta_box_add()
{
    add_meta_box('mllp_album-meta-box-id', 'Album Details', 'MLLPlayer_album_meta_box_render', 'mllp_album', 'normal', 'high');
}

function MLLPlayer_album_meta_box_render($post)
{
    $albumvalues = get_post_custom($post->ID);
    $artist      = isset($albumvalues['artist']) ? esc_attr($albumvalues['artist'][0]) : '';
    $order       = isset($albumvalues['order']) ? esc_attr($albumvalues['order'][0]) : '';
    $available   = isset($albumvalues['available']) ? esc_attr($albumvalues['available'][0]) : '';
    $file        = isset($albumvalues['file']) ? esc_attr($albumvalues['file'][0]) : '';
    wp_nonce_field('mllp_album_meta_box_nonce', 'meta_box_nonce');
?> 
        <p>
        <label for="order">Order</label>
        <input type="text" name="order" id="order" value="<?php
    echo $order;
?>" /><br> 
        <label for="artist">Artist</label>  
        <select name="artist" id="artist"> 
            <?php
    global $post;
    $tmp_post = $post;
    $myposts  = get_posts('post_type=mllp_artist&numberposts=-1');
    $values   = array(
        ''
    );
    foreach ($myposts as $post):
        array_push($values, get_the_title($post->id));
    endforeach;
    $post = $tmp_post;
    unset($values[0]);
    foreach ($values as $value):
?>
                <option value="<?php
        echo $value;
?>" <?php
        selected($artist, $value);
?>><?php
        echo $value;
?></option>
            <?php
    endforeach;
?>  
        </select>
        
        <br>
        <label for="available">Available As</label>
        <select name="available" id="available">
            <option value="nodl" <?php
    selected($available, 'nodl');
?>>Streaming Only</option>
            <option value="dl" <?php
    selected($available, 'dl');
?>>Download</option>
        </select>
        <br>
                <label for="file">File URL</label>
        <input type="text" name="file" id="file" value="<?php
    echo $file;
?>" />
    </p>  
        <?php
}

function MLLPlayer_album_meta_box_save($post_id)
{
    // Bail if we're doing an auto save  
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    
    // if our nonce isn't there, or we can't verify it, bail 
    if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'mllp_album_meta_box_nonce'))
        return;
    
    // if our current user can't edit this post, bail  
    if (!current_user_can('edit_post'))
        return;
    
    // Make sure your data is set before trying to save it  
    
    if (isset($_POST['artist']))
        update_post_meta($post_id, 'artist', esc_attr($_POST['artist']));
     
    if (isset($_POST['order']))
        update_post_meta($post_id, 'order', esc_attr($_POST['order']));
    if (isset($_POST['available']))
        update_post_meta($post_id, 'available', esc_attr($_POST['available']));
    if (isset($_POST['file']))
        update_post_meta($post_id, 'file', esc_attr($_POST['file']));
}

function MLLPlayer_album_edit_columns($columns)
{
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Title"
    );
    
    return $columns;
}

// Make it happen
add_action('save_post', 'MLLPlayer_album_meta_box_save');
add_action('init', 'MLLPlayer_post_type_album');
add_action('add_meta_boxes', 'MLLPlayer_album_meta_box_add');
add_filter("manage_edit-album_columns", "MLLPlayer_album_edit_columns");

// Artist post type, edit columns

function MLLPlayer_post_type_artist()
{
    // Creates artist post type
    register_post_type('mllp_artist', array(
        'label' => 'Artists',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array(
            'slug' => 'music/artists'
        ),
        'query_var' => true,
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'comments',
            'excerpt'
        )
    ));
}

add_action('init', 'MLLPlayer_post_type_artist');

add_filter("manage_edit-artist_columns", "MLLPlayer_artist_edit_columns");

function MLLPlayer_artist_edit_columns($columns)
{
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => "Name"
    );
    
    return $columns;
}
?>