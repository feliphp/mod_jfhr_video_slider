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
$document = JFactory::getDocument();
$js_shadowbox = JURI::root () . "modules/mod_jfhr_video_slider/assets/shadowbox.js";
$js_circle = JURI::root () . "modules/mod_jfhr_video_slider/assets/jquery.cycle.lite.js";
$cssurl = JURI::root().'modules/mod_jfhr_video_slider/themes/default/css/jfhr_video_slider.css';
$document->addStyleSheet($cssurl);
echo "<script type='text/javascript' src='" . $js_shadowbox . "'></script>";
echo "<script type='text/javascript' src='" . $js_circle . "'></script>";
?>
<script type="text/javascript">
Shadowbox.init();
</script>
<style>
#biframe {
	position: absolute;
	width: <?php echo$shadow_width. "px"; ?>;
	height: <?php echo$shadow_height. "px"; ?>;
	z-index: 11;
}
li.slider-video-joe-img {
	position: relative;
	width: <?php echo$width; ?> ! important;
	height: <?php echo$height; ?>;
	margin-top: -8px !important;
}
#video-slide-jfhr {
	position: relative;
	max-width: <?php echo$width; ?> ! important;
	height: <?php echo $height;?>;
}

#video-slider-joe {
	-webkit-box-shadow: 0px 1px 1px 0px rgba(117, 117, 117, 1);
	-moz-box-shadow: 0px 1px 1px 0px rgba(117, 117, 117, 1);
	box-shadow: 0px 1px 1px 0px rgba(117, 117, 117, 1);
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
	margin-top: -23px;
	text-align: center;
	line-height: 16px;
	height: 16px !important;
	width: 25px;
	margin-left: <?php $viw=$video_img_w +2; echo $viw."px"; ?> ! important;
	background-color: #000000;
	color: #C0C0C0;
}

#video-slide-jfhr ul#vnav li#vprev a {
	left: 15px;
	width: 30px;
	height: 30px;
	margin-top: -20px;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-size: 16px;
	font-weight: 100;
	line-height: 27px;
	color: #273746;
	text-align: center;
	background: #F8F9F9;
	border: 3px solid #fff;
	-webkit-border-radius: 23px;
	-moz-border-radius: 23px;
	border-radius: 23px;
	opacity: .5;
	text-decoration: none; //
	background: url(<?php echo "'" . $im_prev . "'"; ?>);
}

#video-slide-jfhr ul#vnav li#vnext a {
	width: 30px;
	height: 30px;
	margin-top: -20px;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-size: 16px;
	font-weight: 100;
	line-height: 27px;
	color: #273746;
	text-align: center;
	background: #F8F9F9;
	border: 3px solid #fff;
	-webkit-border-radius: 23px;
	-moz-border-radius: 23px;
	border-radius: 23px;
	opacity: .5;
	text-decoration: none; //
	background: url(<?php echo "'" . $im_next . "'";?>);
}
</style>
<?php

if ($tp == 1) {
	// ocultar
} else {
	echo '<hr style="position: relative; width: 320px; border-top: 3px solid #FA6F28; top: -11px; margin-left: -10px;">';
	echo "<div id='video-slide-jfhr'>";
	echo "<ul id='vnav'>";
	echo "<li id='vprev'><a href='#'><</a></li>";
	echo "<li id='vnext'><a href='#'>></a></li>";
	echo "</ul>";
	
	// echo "<span id='play-video'><img src='".JURI::root()."/modules/mod_jfhr_video_slider/assets/play.png' height='50px' width='50px'></span>";
	echo " <ul id='video-slider-joe'>";
	foreach ( $sliders as $slider ) {
		$id_art = $slider [0];
		$data = modJFHRVideoSliderHelper::getDataJFHRVideos ( $id_art );
		$da = json_decode ( $data );
		$idyt = explode ( ",", $da );
		$id_youtube = substr ( $idyt [0], 18, - 1 );
		$id_youtube = str_replace ( '"', '', $id_youtube );
		
		if ($id_youtube == '') {
			// echo "<li class='slider-video-joe-img'><img src='".$img_small."' height='".$video_img_h."' width='".$video_img_w."'></li>";
		} else {
			
			echo "<li class='slider-video-joe-img'><center><a href='https://www.youtube.com/v/" . $id_youtube . "&rel=0&autoplay=" . $youtube_autoplay . "&showinfo=" . $youtube_shadow_title . "&controls=" . $youtube_controls . "&modestbranding=" . $youtube_logo . "' rel='shadowbox;width=$shadow_width;height=$shadow_height;' target='" . $target_url . "' id='iframe-yt'><img src='https://img.youtube.com/vi/" . $id_youtube . "/0.jpg' height='" . $video_img_h . "px' width='" . $video_img_w . "px'></a></center>";
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
				// PT3M45S
				preg_match_all ( '/(\d+)/', $timeYt, $parts );
				// no horas
				if ($parts [0] [2] == "") {
					// no minutos
					if ($parts [0] [1] == "") {
						// segundos
						$formTimeYt = "" . $parts [0] [0];
					} else {
						// minutos y segundos
						$formTimeYt = $parts [0] [0] . ":" . $parts [0] [1];
					}
				} else {
					// segundos, minutos y horas
					$formTimeYt = $parts [0] [0] . ":" . $parts [0] [1] . ":" . $parts [0] [2];
				}
				echo "<div id='time-yt'>" . $formTimeYt . "</div>";
				echo "<div id='title-yt'><strong>" . $title . "</strong></div>";
				echo "<div id='channel-yt'>" . $channelTitle . "</div>";
				echo "<div id='statistics-yt'>" . $visualizaciones . " visualizaciones - " . $sinceYt . "</div></li>";
				
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
	}
	
	echo "</ul>";
	// echo "<div id='image-yt'><a href='http://www.youtube.com/watch_popup?v=".$id_youtube."&rel=0&autoplay=".$youtube_autoplay."&showinfo=".$youtube_shadow_title."&controls=".$youtube_controls."&modestbranding=".$youtube_logo."&fs=1'>&nbsp;&nbsp;&nbsp;</a></div>";
	echo "<a href='http://www.youtube.com/watch_popup?v=" . $id_youtube . "&rel=0&autoplay=" . $youtube_autoplay . "&showinfo=" . $youtube_shadow_title . "&controls=" . $youtube_controls . "&modestbranding=" . $youtube_logo . "&fs=1'>&nbsp;&nbsp;&nbsp;</a>";
}
?>

<script type="text/javascript">//<![CDATA[
$(function(){
   $('#video-slider-joe li:gt(0)').hide();
   $('#video-slider-joe').loopedSlider({  
        autoStart: <?php  echo $time;?>  
   });  
});

$("ul#video-slider-joe").cycle({
	fx: 'fade',
	pause: 1,
	prev: '#vprev',
	next: '#vnext'
});

$("#video-slide-jfhr").hover(function() {
	$("ul#vnav").fadeIn(5);
	//$("#image-yt").hide();
	},
		function() {
	$("ul#vnav").fadeOut(5);
	});
	
$("#video-slide-jfhr").click(function() {
	$("#image-yt").show();
	});
	
$("#sb-nav-close").click(function() {
	//$("#image-yt").hide();
});

//]]></script>