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
	// if (isset($_GET['dbg'])) print_r($this->category);
	foreach ($this->items as $r) {

		if ($r->images!='') $im = json_decode($r->images);
		$imf = $im->image_intro;
		$img = '';
		// JText::_('DATE_FORMAT_TPL1')
		if ($imf!='') $img="<img src=\"$imf\" >";
		echo '<div class="article"><h3>'.$r->title.'</h3>'		
		.'<div>'.$img
		.'<div class="date">'.JText::sprintf(JHtml::_('date', $r->publish_up, 'j F Y')).'</div>'
		.$r->introtext.'</div>'		
		.'<div style="clear:both"></div>'
		.'<a class="btn btn-primary" href="articles/'.$r->id.'-'.$r->alias.'">Read more</a>'
		.'</div>';
	}
	echo '</div>';
	
	//echo $this->pageNav->getListFooter(); //Displays a nice footer
	echo '<div class="pagination">';
	if (isset($this->pageNav)) echo $this->pageNav->getPagesLinks();
	echo '</div>';
	//echo $this->pageNav->getPagesCounter();
?>


