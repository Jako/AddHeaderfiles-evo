<?php
/*
 * AddHeaderfiles
 * Puts CSS or JS in the head of a document
 * License GPL
 * Based upon: http://www.partout.info/css_modx.html
 * Version 0.3.3 (21. July 2010)
 * Authors: Mithrandir and Jako
 * See http://www.modxcms.de/forum/comments.php?DiscussionID=926&page=1#Item_4
 * and following posts for details
 *
 * Parameters:
 * &addcode - name(s) of external file(s), script position and css media
 * &sep - separator for styles
 * &sepmed - seperator for medias and script position
 */

// Options - change default media for css here:
$mediadefault = 'screen, tv, projection';

// Check Parameters and set them to default values
$sep = (isset($sep)) ? $sep : ';';
$sepmed = (isset($sepmed)) ? $sepmed : '|';
$addcode = (isset($addcode)) ? $addcode : '';

// if the list of files is stored in a chunk:
if($modx->getChunk($addcode)) {
	$addcode = $modx->getChunk($addcode);
}

$parts = array();
if((strpos(strtolower($addcode), '<script') !== false) || (strpos(strtolower($addcode), '<style') !== false)) {
	$parts[] = $addcode;
} else {
	$parts = explode($sep, $addcode);
}

foreach($parts as $part) {
	$part = explode($sepmed, $part, 2);
	if($modx->getChunk($part[0])) {
		// part of the parameterchain is a chunkname
		$part[0] = $modx->getChunk($part[0]);
		if(strpos(strtolower($part[0]), '<style') !== false) {
			$modx->regClientCSS($part[0]);
		} else {
			if($part[1] != 'end') {
				$modx->regClientStartupScript($part[0]);
			} else {
				$modx->regClientScript($part[0]);
			}
		}
	} else {
		// otherwhise it is treated as a filename
		if(end(explode('.', $part[0])) == 'css') {
			$modx->regClientCSS($part[0], ((isset($part[1])) ? $part[1] : $mediadefault));
		} else {
			if($part[1] != 'end') {
				$modx->regClientStartupScript($part[0]);
			} else {
				$modx->regClientScript($part[0]);
			}
		}
	}
}

return "";
?>