<?php
/*
Plugin Name:  UPLOADUJ.net player
Plugin URI:   https://github.com/KASTINpl/UnetPlayerBBCode
Description:  [unet download="link do pobrania" readonly="1"] link do uploaduj.net [/unet] - parametry czyli readonly="" (brak linka do pobierania) oraz download="" (link do pobierania, jeśli puty - link do uploaduj.net) są nieobowiązkowe
Version:      4.0
Author:       UPLOADUJ.net
Author URI:   http://uploaduj.net
*/

class UnetPlayerBBCode {

	// Plugin initialization
	function __construct() {
		if ( !function_exists('add_shortcode') ) return;

		add_shortcode( 'unet' , array(&$this, 'shortcode_unet') );
	}

	// No-name attribute fixing
	function attributefix( $atts = array() ) {
		if ( empty($atts[0]) ) return $atts;

		if ( 0 !== preg_match( '#=("|\')(.*?)("|\')#', $atts[0], $match ) )
			$atts[0] = $match[2];

		return $atts;
	}

	function get_unet_player($url, $atts){

		$noFollow = empty($atts['download']);
		if (!$noFollow) {
			$url_download = $atts['download'];
		} else {
			$url_download = $url;
		}

		$noDownload = !empty($atts['readonly']);

		$r = "<!--ad-->";
		$r .= '<div class="_unetPlayer" data-url="'.$url.'" data-theme="black"></div>
<script type="text/javascript" async="async" src="http://s.uploaduj.net/_unetPlayer.js"></script><br>';

		if ( $noDownload )  $r .= '<p class="alert alert-warning">Plik dostępny w wersji tylko do odsłuchu!</p>';
		else { $r .= "<p class=\"center-block text-center\"><a";
			if ($noFollow) $r .= " rel=\"nofollow\"";
			$r .= " target=\"_blank\" href=\"$url_download\" title=\"$nazwa mp3 download\" class=\"btn btn-success\"><i class=\"glyphicon glyphicon-download\"></i> Pobierz <strong>$nazwa</strong></a></p>";
		}
		$r .= "";

		return $r;
	}


	// Bold shortcode
	function shortcode_unet( $atts = array(), $content = NULL ) {
		if ( NULL === $content ) return '';

		return $this->get_unet_player( do_shortcode( $content ), $atts );
	}
}

// Start this plugin once all other plugins are fully loaded
add_action( 'plugins_loaded', create_function( '', 'global $UnetPlayerBBCode; $UnetPlayerBBCode = new UnetPlayerBBCode();' ) );
