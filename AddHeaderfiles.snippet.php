<?php
/*
 * AddHeaderfiles
 * Adds CSS or JS in a document (at the end of the head or the end of the body)
 * License GPL
 * Based upon: http://www.partout.info/css_modx.html
 * Version 0.4.1 (3. August 2011)
 * Authors: Jako and Mithrandir
 * See http://www.modxcms.de/forum/comments.php?DiscussionID=926&page=1#Item_4
 * and following posts for details
 *
 * Parameters:
 * &addcode - name(s) of external file(s) or chunkname(s) separated by semicolon
              these external files can have a position setting or media type 
              separated by pipe
 * &sep -     separator for files/chunknames
 * &sepmed -  seperator for media type or script position
 */

// Options - change default media type in the snippet properties (look into README)
$mediadefault = (isset($mediadefault)) ? $mediadefault : 'screen, tv, projection';

// Check Parameters and set them to default values
$sep = (isset($sep)) ? $sep : ';';
$sepmed = (isset($sepmed)) ? $sepmed : '|';
$addcode = (isset($addcode)) ? $addcode : '';

if(!function_exists('AddHeaderfiles')) {
	function AddHeaderfiles($addcode, $sep, $sepmed, $mediadefault) {
		global $modx;
		

		if((strpos(strtolower($addcode), '<script') !== false) || (strpos(strtolower($addcode), '<style') !== false)) {
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
		foreach($parts as $part) {
			$part = explode($sepmed, trim($part, " \n\r\t"), 2);
			if($chunk = $modx->getChunk($part[0])) {
				// part of the parameterchain is a chunkname
				$part[0] = AddHeaderfiles($chunk, $sep, $sepmed, $mediadefault);
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
				if(substr($part[0], -4) == '.css') {
					$modx->regClientCSS($part[0], (isset($part[1]) ? $part[1] : $mediadefault));
				} else {
					if($part[1] != 'end') {
						$modx->regClientStartupScript($part[0]);
					} else {
						$modx->regClientScript($part[0]);
					}
				}
			}
		}
	}
}

if($addcode != '') {
	$addcode = AddHeaderfiles($addcode, $sep, $sepmed, $mediadefault);
	if(strpos(strtolower($addcode), '<style') !== false) {
		$modx->regClientCSS($addcode);
	} else {
		$modx->regClientStartupScript($addcode);
	}
}
return "";
?>
