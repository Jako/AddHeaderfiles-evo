<?php
/*
 * AddHeaderfiles
 * Adds CSS or JS in a document (at the end of the head or the end of the body)
 * License GPL
 * Based upon: http://www.partout.info/css_modx.html
 * Version 0.4.3 (17. April 2013)
 * Authors: Jako and Mithrandir
 * See http://www.modxcms.de/forum/comments.php?DiscussionID=926&page=1#Item_4
 * and following posts for details
 *
 * Parameters:
 * &addcode - name(s) of external file(s) or chunkname(s) separated by semicolon
 * these external files can have a position setting or media type
 * separated by pipe
 * In direct snippet call some uri chars have to be masked
 * '?' has to be masked as '!q!'
 * '=' has to be masked as '!eq!'
 * '&' has to be masked as '!and!'
 * &sep -     separator for files/chunknames
 * &sepmed -  seperator for media type or script position
 */

// Options - change default media type in the snippet properties (look into README)
$mediadefault = (isset($mediadefault)) ? $mediadefault : 'screen, tv, projection';

// Check Parameters and set them to default values
$sep = (isset($sep)) ? $sep : ';';
$sepmed = (isset($sepmed)) ? $sepmed : '|';
$addcode = (isset($addcode)) ? $addcode : '';

if (!function_exists('AddHeaderfiles')) {

	function AddHeaderfiles($addcode, $sep, $sepmed, $mediadefault) {
		global $modx;

		if ((strpos(strtolower($addcode), '<script') !== FALSE) || (strpos(strtolower($addcode), '<style') !== FALSE) || (strpos(strtolower($addcode), '<!--') !== FALSE)) {
			if (class_exists('PHxParser')) {
				$PHx = new PHxParser();
				$addcode = $PHx->Parse($addcode);
			}
			$addcode = $modx->mergeChunkContent($addcode);
			$addcode = $modx->evalSnippets($addcode);
			$addcode = $modx->mergePlaceholderContent($addcode);

			return $addcode;
		} else {
			$parts = explode($sep, $addcode);
		}
		foreach ($parts as $part) {
			// unmask masked url parameters
			$part = str_replace(array('!q!', '!eq!', '!and!'), array('?', '=', '&'), $part);
			$part = explode($sepmed, trim($part), 2);
			$chunk = $modx->getChunk($part[0]);
			if ($chunk) {
				// part of the parameterchain is a chunkname
				$part[0] = AddHeaderfiles($chunk, $sep, $sepmed, $mediadefault);
				$conditional = (strpos(strtolower($part[0]), '<!--') !== FALSE);
				$style = (strpos(strtolower($part[0]), '<style') !== FALSE);
				$startup = !(isset($part[1]) && $part[1] == 'end');
				switch (TRUE) {
					case ($conditional):
						$modx->regClientScript($part[0], TRUE, $startup);
						break;
					case ($style):
						$modx->regClientScript($part[0], TRUE, TRUE);
						break;
					default:
						$modx->regClientScript($part[0], FALSE, $startup);
						break;
				}
			} else {
				// otherwhise it is treated as a filename
				$style = (substr(trim($part[0]), -4) == '.css');
				$startup = !(isset($part[1]) && $part[1] == 'end');
				switch (TRUE) {
					case ($style):
						$modx->regClientCSS($part[0], (isset($part[1]) ? $part[1] : $mediadefault));
						break;
					default:
						$modx->regClientScript($part[0], FALSE, $startup);
						break;
				}
			}
		}
	}

}

if ($addcode != '') {
	$addcode = AddHeaderfiles($addcode, $sep, $sepmed, $mediadefault);
	if (strpos(strtolower($addcode), '<style') !== FALSE) {
		$modx->regClientCSS($addcode);
	} else {
		$modx->regClientStartupScript($addcode);
	}
}
return '';
?>