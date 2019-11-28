<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
	echo '<div class="articles">';
	//print_r($this->category);
	foreach ($this->items as $r) {

		if ($r->images!='') $im = json_decode($r->images);
		$imf = $im->image_intro;
		$img = '';
		if ($imf!='') $img="<img src=\"$imf\" >";
		echo '<div class="article"><h3>'.$r->title.'</h3><div>'.$img.$r->introtext.'</div>'
		.'<a class="btn btn-primary" href="#">Read more</a>'
		.'</div>';
	}
	echo '</div>';
	
	echo $this->pageNav->getListFooter(  ); //Displays a nice footer
?>
