<?php
/*
Plugin Name:  UPLOADUJ.net player
Plugin URI:   https://github.com/KASTINpl/UnetPlayerBBCode
Description:  [unet download="link do pobrania" readonly="1"] link do uploaduj.net [/unet] - parametry czyli readonly="" (brak linka do pobierania) oraz download="" (link do pobierania, jeśli puty - link do uploaduj.net) są nieobowiązkowe
Version:      4.1
Author:       UPLOADUJ.net
Author URI:   https://uploaduj.net
*/

class UnetPlayerBBCode
{
    /**
     * @param string $url
     * @param array $atts
     * @return string
     */
    function getUnetPlayer($url, array $atts)
    {
        $noFollow = empty($atts['download']);
        if (!$noFollow) {
            $url_download = $atts['download'];
        } else {
            $url_download = $url;
        }

        $noDownload = !empty($atts['readonly']);

        $r = "<!--ad-->";
        $r .= '<div class="_unetPlayer" data-url="' . $url . '" data-theme="black"></div>
<script type="text/javascript" async="async" src="https://s.uploaduj.net/_unetPlayer.js"></script><br>';

        if ($noDownload) $r .= '<p class="alert alert-warning">Plik dostępny w wersji tylko do odsłuchu!</p>';
        else {
            $r .= "<p class=\"center-block text-center\"><a";
            if ($noFollow) $r .= " rel=\"nofollow\"";
            $r .= " target=\"_blank\" href=\"$url_download\" class=\"btn btn-success btn-unet-download-link\"><i class=\"glyphicon glyphicon-download\"></i> Pobierz <strong>$url</strong></a></p>";
        }
        $r .= "";

        return $r;
    }

    /**
     * @param array $atts
     * @param null $content
     * @return string
     */
    public function shortcodeUnet($atts = array(), $content = NULL)
    {
        if (NULL === $content) return '';

        return $this->getUnetPlayer(do_shortcode($content), $atts);
    }
}

// Start this plugin once all other plugins are fully loaded
add_action(
    'plugins_loaded',
    function () {
        if (!function_exists('add_shortcode')) return;

        $unet = new UnetPlayerBBCode();

        add_shortcode('unet', array($unet, 'shortcodeUnet'));
    }
);
