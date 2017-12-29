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
class modJFHRVideoSliderHelper {
	public static function getVideoSlider($params, $en_perms, $order_date, $videos_limit) {
		// Obtain a database connection
		$dba = JFactory::getDbo ();
		// Retrieve the shout
		
		// Access filter
		$user = JFactory::getUser ()->get ( 'id' );
		jimport ( 'joomla.access.access' );
		$groups = JAccess::getGroupsByUser ( $user, false );
		
		foreach ( $groups as $group ) {
			$accessgroup = $group;
		}
		
		// Query Access label
		$queryaccess = $dba->getQuery ( true )->select ( $dba->quoteName ( 'id' ) )->from ( $dba->quoteName ( '#__viewlevels' ) )->where ( "rules LIKE '%" . $accessgroup . "%'" );
		$dba->setQuery ( $queryaccess );
		$access = $dba->loadResult ();
		
		// Query multiple
		$querymultiple = $dba->getQuery ( true )->select ( $dba->quoteName ( 'extension_id' ) )->from ( $dba->quoteName ( '#__extensions' ) )->where ( 'element = "com_accessmanager"' );
		$dba->setQuery ( $querymultiple );
		$querymultiplecount = $dba->loadResult ();
		
		if ($querymultiplecount = ! "") {
			// modo multiple
			$db = JFactory::getDbo ();
			if ($en_perms == '1') {
				$query = $db->getQuery ( true )->select ( $db->quoteName ( array (
						'c.id',
						'c.title',
						'c.hits' 
				) ) )->from ( $db->quoteName ( '#__content', 'c' ) )->join ( 'INNER', $db->quoteName ( '#__accessmanager_rights', 'am' ) . ' ON (' . $db->quoteName ( 'c.id' ) . ' = ' . $db->quoteName ( 'am.item' ) . ')' )->where ( 'c.catid = ' . $db->Quote ( $params ) )->where ( '(am.group = ' . $accessgroup . ' OR am.group = "1")', 'AND' )->where ( 'c.state = ' . 1 )->order ( 'c.publish_up ' . $order_date . '' )->setLimit ( $videos_limit );
			} else {
				$query = $db->getQuery ( true )->select ( $db->quoteName ( array (
						'c.id',
						'c.title',
						'c.hits' 
				) ) )->from ( $db->quoteName ( '#__content', 'c' ) )->join ( 'INNER', $db->quoteName ( '#__accessmanager_rights', 'am' ) . ' ON (' . $db->quoteName ( 'c.id' ) . ' = ' . $db->quoteName ( 'am.item' ) . ')' )->where ( '(am.group = ' . $accessgroup . ' OR am.group = "1")', 'AND' )->where ( 'c.catid = ' . $db->Quote ( $params ) )->order ( 'c.publish_up ' . $order_date . '' )->setLimit ( $videos_limit );
			}
			
			// Prepare the query
			$db->setQuery ( $query );
			// Load the row.
			$results = $db->loadRowList ();
			// Return the Hello
			return $results;
		} else {
			// modo normal
			$db = JFactory::getDbo ();
			if ($en_perms == '1') {
				$query = $db->getQuery ( true )->select ( $db->quoteName ( array (
						'id',
						'title',
						'hits' 
				) ) )->from ( $db->quoteName ( '#__content' ) )->where ( 'catid = ' . $db->Quote ( $params ) )->where ( '(access = ' . $access . ' OR access = "1")', 'AND' )->
				// ->where('access = '. $access.' OR access = 1')
				where ( 'state = ' . 1 )->order ( 'publish_up ' . $order_date . '' )->setLimit ( $videos_limit );
			} else {
				$query = $db->getQuery ( true )->select ( $db->quoteName ( array (
						'id',
						'title',
						'hits' 
				) ) )->from ( $db->quoteName ( '#__content' ) )->where ( '(access = ' . $access . ' OR access = "1")', 'AND' )->where ( 'catid = ' . $db->Quote ( $params ) )->order ( 'publish_up ' . $order_date . '' )->setLimit ( $videos_limit );
			}
			// Prepare the query
			$db->setQuery ( $query );
			// Load the row.
			$results = $db->loadRowList ();
			// Return the Hello
			return $results;
		}
	}
	public static function getDataJFHRVideos($id_article) {
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true )->select ( $db->quoteName ( array (
				'data' 
		) ) )->from ( $db->quoteName ( '#__content_jfhr_videos' ) )->where ( 'article_id = ' . $id_article );
		$db->setQuery ( $query );
		$result = $db->loadResult ();
		return $result;
	}
	public static function youtubeTimeago($time) {
		$delta = time () - $time;
		
		define ( "SECOND", 1 );
		define ( "MINUTE", 60 * SECOND );
		define ( "HOUR", 60 * MINUTE );
		define ( "DAY", 24 * HOUR );
		define ( "MONTH", 30 * DAY );
		
		if ($delta < 1 * MINUTE) {
			return $delta == 1 ? "en este momento" : "hace " . $delta . " segundos ";
		}
		if ($delta < 2 * MINUTE) {
			return "Hace un minuto";
		}
		if ($delta < 45 * MINUTE) {
			return "Hace " . floor ( $delta / MINUTE ) . " minutos";
		}
		if ($delta < 90 * MINUTE) {
			return "Hace una hora";
		}
		if ($delta < 24 * HOUR) {
			return "Hace " . floor ( $delta / HOUR ) . " horas";
		}
		if ($delta < 48 * HOUR) {
			return "Ayer";
		}
		if ($delta < 30 * DAY) {
			return "Hace " . floor ( $delta / DAY ) . " dias";
		}
		if ($delta < 12 * MONTH) {
			$months = floor ( $delta / DAY / 30 );
			return $months <= 1 ? "El mes pasado" : "hace " . $months . " meses";
		} else {
			$years = floor ( $delta / DAY / 365 );
			return $years <= 1 ? "El a&ntilde;o pasado" : "hace " . $years . " a&ntilde;os";
		}
	}
}