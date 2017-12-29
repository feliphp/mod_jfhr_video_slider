<?php
/**
 * @module		mod_jfhr_Video_slider
 * @author-name Jose Felipe Herrera Rodríguez
 * @adapted by  Biaani
 * @copyright	Copyright (C) 2016 Biaani
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access
defined ( '_JEXEC' ) or die ();

require_once dirname ( __FILE__ ) . '/helper.php';

$category = $params->get ( 'video_slide_title', '1' );
$width = $params->get ( 'video_slide_width', '50%' );
$height = $params->get ( 'video_slide_height', '30%' );
$time = $params->get ( 'video_slide_time', '5000' );

$en_perms = $params->get ( 'video_slide_enabled_permissions', '1' );
$youtube_buttons = $params->get ( 'enabled_youtube_buttons', '0' );
$order_date = $params->get ( 'video_slide_order_date', 'DESC' );
$videos_limit = $params->get ( 'videos_limit', '3' );
$video_img_w = $params->get ( 'video_img_slide_width', '260' );
$video_img_h = $params->get ( 'video_img_slide_height', '130' );

$shadow_width = $params->get ( 'video_modal_width', '800' );
$shadow_height = $params->get ( 'video_modal_height', '450' );
$youtube_autoplay = $params->get ( 'enabled_youtube_autoplay', '1' );
$youtube_shadow_title = $params->get ( 'enabled_youtube_shadow_title', '0' );
$youtube_controls = $params->get ( 'enabled_youtube_shadow_controls', '1' );
$youtube_logo = $params->get ( 'enabled_youtube_shadow_logo', '0' );

$sliders = modJFHRVideoSliderHelper::getVideoSlider ( $category, $en_perms, $order_date, $videos_limit );

// variable para vista previa del template
$tp = $_GET ['tp'];

if ($videos_limit == '1') {
	require JModuleHelper::getLayoutPath ( 'mod_jfhr_video_slider', 'uno' );
} else {
	require JModuleHelper::getLayoutPath ( 'mod_jfhr_video_slider' );
}