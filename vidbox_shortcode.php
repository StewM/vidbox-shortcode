<?php
/*
Plugin Name: Chooch VidBox Shortcode
Plugin URI: https://github.com/StewM/vidbox-shortcode
Description: A plugin to add a video lightbox shortcode and button to the editor
Version: 1.1
Author: Stewart Mink
Author URI: http://stewartmink.com
*/

/**
* vidbox_shortcode
*/
class vidbox_shortcode{
    /**
     * $shortcode_tag
     * holds the name of the shortcode tag
     * @var string
     */
    public $shortcode_tag = 'vidbox';

    /**
     * __construct
     * class constructor will set the needed filter and action hooks
     *
     * @param array $args
     */
    function __construct($args = array()){
      //add shortcode
      add_shortcode( $this->shortcode_tag, array( $this, 'shortcode_handler' ) );

      if ( is_admin() ){
          add_action('admin_head', array( $this, 'admin_head') );
          add_action( 'admin_enqueue_scripts', array($this , 'admin_enqueue_scripts' ) );
      }
    }

    /**
     * shortcode_handler
     * @param  array  $atts shortcode attributes
     * @param  string $content shortcode content
     * @return string
     */
    function shortcode_handler($atts , $content = null){
      ob_start();
  		$atts = shortcode_atts(
  			array(
  				'video_url' => '',
  				'auto_play' => '',
  				'alt_text' => '',
  			),
  			$atts
  		);

		  $video = $atts['video_url'];

  		if( preg_match("/(youtube.com)/", $video) ){ // if long youtube video link, like https://www.youtube.com/watch?v=sd0grLQ4voU
  	    		$video_id = explode("v=", preg_replace("/(&)+(.*)/", null, $video) );
  	    		$video_id = $video_id[1];
  		}

  		else{
  	    		if( preg_match("/(youtu.be)/", $video) ){ // if short youtube video link, like http://youtu.be/sd0grLQ4voU
  	        		$video_id = explode("/", preg_replace("/(&)+(.*)/", null, $video) );
  	        		$video_id = $video_id[3];
  	   		}
  		}

  		$output .='<div class="videoplayer"><a class="'.$atts['auto_play'].'" href="'.$atts['video_url'].'"><span></span><img src="http://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg" alt="'. $atts['alt_text'] .'" /></a></div><div class="videoclear"></div>';
  		ob_clean();
  		return $output;
    }

    /**
     * admin_head
     * calls your functions into the correct filters
     * @return [type] [description]
     */
    function admin_head() {
      // check user permissions
      if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
          return;
      }

      // check if WYSIWYG is enabled
      if ( 'true' == get_user_option( 'rich_editing' ) ) {
          add_filter( 'mce_external_plugins', array( $this ,'mce_external_plugins' ) );
          add_filter( 'mce_buttons', array($this, 'mce_buttons' ) );
      }
    }

    /**
     * mce_external_plugins
     * Adds our tinymce plugin
     * @param  array $plugin_array
     * @return array
     */
    function mce_external_plugins( $plugin_array ) {
      $plugin_array[$this->shortcode_tag] = plugins_url( 'js/mce-button.js' , __FILE__ );
      return $plugin_array;
    }

    /**
     * mce_buttons
     * Adds our tinymce button
     * @param  array $buttons
     * @return array
     */
    function mce_buttons( $buttons ) {
      array_push( $buttons, $this->shortcode_tag );
      return $buttons;
    }

    /**
     * admin_enqueue_scripts
     * Used to enqueue custom styles
     * @return void
     */
    function admin_enqueue_scripts(){
      wp_enqueue_style('vidbox_shortcode', plugins_url( 'css/mce-button.css' , __FILE__ ) );
    }
}//end class

new vidbox_shortcode();
