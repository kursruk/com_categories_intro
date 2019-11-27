<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Categories
 * @author     kursruk <kursruk@gmail.com>
 * @copyright  2019 (c) Richard Hughes
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;

// $heading = $this->params->get('show_page_heading',1);
echo  '<h1 class="item_title">'.JFactory::getApplication()->getMenu()->getActive()->title.'</h1>';
// echo  '<div class="item_title">'.$this->params->get('fotomotodescription').'</div>';
?>
<style type="text/css">
	.category-item {
		margin-right: <?=$this->params->get('columnmargin')?>pt;
		margin-bottom: <?=$this->params->get('columnmargin')?>pt;
		width: <?=$this->params->get('imagewidth')?>px;
	}
</style>
<div class="categories-img-list">	
<?php
	// echo JPATH_ROOT;
    foreach ($this->items as $img)
    {  	?>			
				<div class="category-item"> 					
					<?php
						if ($img->src!=='')
						{					
							echo '<img  data-src="'.$img->src.'" id="fimgx'.$img->id
							.'" src="'.$img->resized.'" alt="'
							.htmlspecialchars($img->title).'" />';
							echo '<div class="category-desc">'
							."<h2>$img->title</h2>"
							.$img->description.'</div>';
						}
					?>					
				</div>
			<?php
    }
?>
	<div style="clear:both"></div>
</div>




