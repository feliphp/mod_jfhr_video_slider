<?php
/**
 * @module		mod_jfhr_video_slider
 * @author-name Jose Felipe Herrera Rodríguez
 * @adapted by  Biaani
 * @copyright	Copyright (C) 2016 Biaani
 * @license		GNU/GPL, see http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */

// No direct access
defined ( '_JEXEC' ) or die ();
echo "<script type='text/javascript' src='" . JURI::root () . "modules/mod_jfhr_video_slider/assets/shadowbox.js'></script>";
?>
<script type="text/javascript">
Shadowbox.init();
</script>
<style>
.slider-video-joe-img {
	position: relative;
	max-width: <?php echo$width; ?> ! important;
	height: <?php echo$height; ?>;
	margin-left: auto !important;
	margin-right: auto !important;
	top: 15px !important;
}

#video-slider-joe {
	-webkit-box-shadow: -6px 7px 25px -5px rgba(117, 117, 117, 1);
	-moz-box-shadow: -6px 7px 25px -5px rgba(117, 117, 117, 1);
	box-shadow: -6px 7px 25px -5px rgba(117, 117, 117, 1);
	width: <?php echo$width; ?>;
	height: <?php echo$height; ?>;
	margin-top: -10px !important;
	margin-left: -10px !important;
}

.video-slider-joe-img img {
	position: relative;
	width: <?php echo$width; ?> ! important;
	height: <?php echo $height; ?>;
}

#time-yt {
	position: relative;
	font-size: 11px;
	margin-top: -8px;
	text-align: center;
	line-height: 16px;
	height: 16px !important;
	width: 25px;
	margin-left: <?php $viw=$video_img_w +42; echo $viw."px"; ?> ! important;
	background-color: #000000;
	color: #C0C0C0;
}
</style>
<?php
if ($tp == 1) {
	// ocultar
} else {
	echo " <ul id='video-slider-joe'>";
	foreach ( $sliders as $slider ) {
		$id_art = $slider [0];
		$data = modJFHRVideoSliderHelper::getDataJFHRVideos ( $id_art );
		$da = json_decode ( $data );
		$idyt = explode ( ",", $da );
		$id_youtube = substr ( $idyt [0], 18, - 1 );
		
		if ($id_youtube == '') {
			// echo "<li class='slider-video-joe-img'><img src='".$img_small."' height='".$video_img_h."' width='".$video_img_w."'></li>";
		} else {
			echo "<span class='slider-video-joe-img'><center><a href='https://www.youtube.com/v/" . $id_youtube . "&rel=0&autoplay=" . $youtube_autoplay . "&showinfo=" . $youtube_shadow_title . "&controls=" . $youtube_controls . "&modestbranding=" . $youtube_logo . "' rel='shadowbox;width=$shadow_width;height=$shadow_height;' target='" . $target_url . "'><img src='https://img.youtube.com/vi/" . $id_youtube . "/0.jpg' height='" . $video_img_h . "px' width='" . $video_img_w . "px'></a></center></span>";
		}
	}
	
	// api google
	$config = JFactory::getConfig ();
	$urlyoutube = "https://www.googleapis.com/youtube/v3/videos?id=" . $id_youtube . "&key=" . $config->get ( 'jfhr_youtube_key' ) . "&part=snippet,contentDetails,statistics,status";
	if (extension_loaded ( 'curl' )) {
		// create a new cURL resource
		$ch = curl_init ();
		
		// set URL and other appropriate options
		curl_setopt ( $ch, CURLOPT_URL, $urlyoutube );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		// Setting cURL's option to return the webpage data
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		
		// grab URL and pass it to the browser
		if ($json = curl_exec ( $ch )) {
			$response_decoded = json_decode ( $json );
			// video title
			$title = $response_decoded->items [0]->snippet->title;
			// video channel
			$channelTitle = $response_decoded->items [0]->snippet->channelTitle;
			// video since published
			$dateYt = $response_decoded->items [0]->snippet->publishedAt;
			$dateExtractYt = substr ( $dateYt, 0, - 14 );
			$dateExtractYtUn = strtotime ( $dateExtractYt );
			$sinceYt = modJFHRVideoSliderHelper::youtubeTimeago ( $dateExtractYtUn );
			// video statistics
			$visualizaciones = $response_decoded->items [0]->statistics->viewCount;
			// video time
			$timeYt = $response_decoded->items [0]->contentDetails->duration;
			preg_match_all ( '/(\d+)/', $timeYt, $parts ); // PT3M45S
			if ($parts [0] [2] == "") {
				$formTimeYt = $parts [0] [0] . ":" . $parts [0] [1];
			} else {
				$formTimeYt = $parts [0] [0] . ":" . $parts [0] [1] . ":" . $parts [0] [2];
			}
			echo "<div id='time-yt-o'>" . $formTimeYt . "</div>";
			echo "<div id='title-yt-o'><strong>" . $title . "</strong></div>";
			echo "<div id='channel-yt-o'>" . $channelTitle . "</div>";
			echo "<div id='statistics-yt-o'>" . $visualizaciones . " visualizaciones - " . $sinceYt . "</div>";
			
			if (! ($data = @json_decode ( $json )) instanceof stdClass) {
				trigger_error ( 'Unable to decode json. ' . print_r ( error_get_last (), true ) );
			}
		} 

		else
			trigger_error ( 'CUrl Error:' . curl_error ( $ch ) );
			
			// close cURL resource, and free up system resources
		curl_close ( $ch );
	} 

	else
		trigger_error ( 'CUrl unsupported', E_USER_WARNING );
	
	echo "</ul>";
}
?>

<script type="text/javascript">//<![CDATA[
$(function(){
    $('#video-slider-joe li:gt(0)').hide();
});
//]]></script>