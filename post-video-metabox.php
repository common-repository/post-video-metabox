<?php

/*
Plugin Name: Post Video Metabox
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Add Video URL Metabox to the post format video and show the video player in the single post thumbnail which can click to play on the thumbnail.
Version: 2.0.0
Author: palagornp
Author URI: http://palamike.com
License: GPL2
*/

define('__PVT_PLUGIN_PATH__',dirname(__FILE__));
define('__PVT_PLUGIN_URL__',plugin_dir_url( __FILE__ ));

require_once(__PVT_PLUGIN_PATH__.'/inc/classes/PVT_Plugin.php');

new PVT_Plugin();