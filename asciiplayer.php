<?php
/*
Plugin Name: Asciiplayer Shortcode
Version: 1.0
Plugin URI: https://github.com/pagely/asciiplayer-wpplugin
Author: Joshua Strebel
Author URI: https://pagely.com/
Description: asciinema-player Shortcode for Wordpress.
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

/*
Orig_Plugin Name: asciiplayer_modified
Orig_Version: 0.3.1
Orig_Plugin URI: https://wordpress.org/plugins/asciiplayer
Orig_Author: Jorge Maldonado Ventura
Orig_Author URI: https://www.freakspot.net/
Orig_Description: asciinema-player for Wordpress.
Orig_License: GPLv3
Orig_License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/
defined('ABSPATH') or die('No script kiddies please!');

function asciiplayer_enqueue_scripts() {
    wp_register_script('asciinema-player-js', plugins_url('asciinema-player.js', __FILE__), array(), '2.4.1', true);
    wp_register_style('asciinema-player-css', plugins_url('asciinema-player.css', __FILE__), array(), '2.4.1');

    //wp_enqueue_script('asciinema-player-js');
    //wp_enqueue_style('asciinema-player-css');
}

add_action('wp_enqueue_scripts', 'asciiplayer_enqueue_scripts');

// asciiplayer shortcode
function asciinema_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'cast_url' => 'something.cast',
		'autoplay' => 'false',
    'preload' => 'true',
    'playersize'  => 'big'
	), $atts );

  wp_enqueue_script('asciinema-player-js');
  wp_enqueue_style('asciinema-player-css');

	return "
  <asciinema-player src='{$a['cast_url']}' autoplay='{$a['autoplay']}' preload='{$a['preload']}' size='{$a['playersize']}'></asciinema-player>
  ";
}
add_shortcode( 'asciiplayer', 'asciinema_shortcode' );


// allow .cast files in media uplaoder
function my_myme_types( $mime_types ) {
  $mime_types['cast'] = 'application/json'; // Adding .json extension
  return $mime_types;
}

// add tinymce editor - https://madebydenis.com/adding-shortcode-button-to-tinymce-editor/
add_action( 'plugins_loaded', 'ascii_player_setup' );


if ( ! function_exists( 'ascii_player_setup' ) ) {
    function ascii_player_setup() {

        add_action( 'init', 'asciiplayer_button' );

    }
}

/********* TinyMCE Buttons ***********/
if ( ! function_exists( 'asciiplayer_button' ) ) {
    function asciiplayer_button() {
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }

        if ( get_user_option( 'rich_editing' ) !== 'true' ) {
            return;
        }

        add_filter( 'mce_external_plugins', 'asciiplayer_add_buttons' );
        add_filter( 'mce_buttons', 'asciiplayer_register_buttons' );
    }
}

if ( ! function_exists( 'asciiplayer_add_buttons' ) ) {
    function asciiplayer_add_buttons( $plugin_array ) {
        $plugin_array['asciiplayerbutton'] = plugin_dir_url( __FILE__ ).'/tinymce_buttons.js';
        return $plugin_array;
    }
}

if ( ! function_exists( 'asciiplayer_register_buttons' ) ) {
    function asciiplayer_register_buttons( $buttons ) {
        array_push( $buttons, 'asciiplayerbutton' );
        return $buttons;
    }
}

add_action ( 'after_wp_tiny_mce', 'asciiplayer_tinymce_extra_vars' );

if ( !function_exists( 'asciiplayer_tinymce_extra_vars' ) ) {
	function asciiplayer_tinymce_extra_vars() { ?>
		<script type="text/javascript">
			var tinyMCE_object = <?php echo json_encode(
				array(
					'button_name' => esc_html__('AsciiPlayer', 'asciiplayerslug'),
					'button_title' => esc_html__('AsciiPlayer Embed', 'asciiplayerslug'),
					'image_title' => esc_html__('.cast File', 'asciiplayerslug'),
					'image_button_title' => esc_html__('Upload .cast', 'asciiplayerslug'),
				)
				);
			?>;
		</script><?php
	}
}
