<?php

/**
 * @version    CVS: 1.0.0
 * @package    categories_intro_gallery
 * @author     kursruk <kursruk@gmail.com>
 * @copyright  2019 (c) Richard Hughes
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class Categories_introViewCategories extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $state;

	protected $item;

	protected $form;

	protected $params;

	protected $canSave;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$app  = Factory::getApplication();
		$user = Factory::getUser();

		$this->state   = $this->get('State');
		$this->item    = $this->get('Item');
		$this->params  = $app->getParams('com_categories_intro');
		$this->canSave = $this->get('CanSave');
		

		// Check for errors.
		// if (count($errors = $this->get('Errors')))
		// {
		// 	throw new Exception(implode("\n", $errors));
		// }
		$this->items = [];
		$this->_prepareDocument();
		if ($this->id!='') {
			$this->maxLevelcat = 1;			
			$this->getIntroArticles();
			parent::display('items');
		} else {
			$this->getIntroItems();
			parent::display($tpl);
		}
		
	}

	function retResizedImage($imagePath, $new_width=450)
	{   $fileName = pathinfo($imagePath, PATHINFO_FILENAME);
		$fullPath = pathinfo($imagePath, PATHINFO_DIRNAME) . "/" . $fileName . "_w${new_width}.jpg";

		if (file_exists($fullPath)) {
			return $fullPath;
		}

		$image = imagecreatefromjpeg($imagePath);
		if ($image == false) {
			return null;
		}

		$width = imagesx($image);
		$height = imagesy($image);

		$imageResized = imagecreatetruecolor($width, $height);
		if ($imageResized == false) {
			return null;
		}

		// $image = imagecreatetruecolor($width, $height);
		$imageResized = imagescale($image, $new_width, -1, IMG_BICUBIC);
		touch($fullPath);
		$write = imagejpeg($imageResized, $fullPath);
		if (!$write) {
			imagedestroy($imageResized);
			return null;
		}
		imagedestroy($imageResized);
		return $fullPath;
	}

	function getAllArticles ($db, $lim0, $lim) 
	{
		$query = $db->getQuery(true);
		$query->select( ['count(*) as t'] )
		->from($db->quoteName('#__content', 'a'))
		->where(' a.state=1 and a.publish_down<=current_timestamp ');
	
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$total = $db->loadResult();

		if ($total>0)
		{

		// $app = Factory::getApplication();
		$query = $db->getQuery(true);
		$query->select( ['a.id', 'a.title', 'a.alias', 'a.introtext', 
				'a.images',	'a.publish_up' ] )
				->from($db->quoteName('#__content', 'a'))
				->where(' a.state=1 and a.publish_down<=current_timestamp and a.catid='
					.$db->quote($this->params->get('category') ) 
				)
				->order(' a.publish_up desc')
				->setLimit($lim, $lim0); // limit, offset
			
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
		}		
		return $total;
	}

	function getArticlesByTags($db, $lim0, $lim) 
	{	$query = $db->getQuery(true);
		$sql = ' count(*) as t from (select a.id as t from  #__categories c '."\n"
		.' join #__contentitem_tag_map m  '."\n"
		." 	 on m.content_item_id=c.id AND m.type_alias='com_content.category'"."\n"
		.' join #__contentitem_tag_map m2 '."\n"
		."   on m2.tag_id = m.tag_id AND m2.type_alias='com_content.article'"."\n"
		.' join #__content a on a.id = m2.content_item_id '."\n"
		.' where c.alias = '.$db->quote($this->id)."\n"
		.' AND a.state=1 and a.publish_down<=current_timestamp '."\n" 
		.' group by a.id) as y';

		$query->select($sql);
	
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$total = $db->loadResult();

		if ($total>0)
		{

		/*		
			select 
			a.id,
			a.title,
			a.alias,    
			a.introtext, 
			a.images,
			a.publish_up
		from ja_categories c
		join ja_contentitem_tag_map m on 
			m.type_alias='com_content.category' and m.content_item_id=c.id
		join ja_contentitem_tag_map m2 on m2.type_alias='com_content.article' and
			m2.tag_id = m.tag_id
		join ja_content a on a.id = m2.content_item_id
		where c.alias='category-2' and a.state=1 and a.publish_down<=current_timestamp
		order by a.publish_up desc;
		*/

		// $app = Factory::getApplication();
		$query = $db->getQuery(true);
		$query->select( ['DISTINCT a.id', 'a.title', 'a.alias', 'a.introtext', 
				'a.images',	'a.publish_up' ] )
				->from($db->quoteName('#__categories', 'c'))
				->join('INNER', $db->quoteName('#__contentitem_tag_map', 'm') 
				." ON m.content_item_id=c.id AND m.type_alias='com_content.category' ")
				->join('INNER', $db->quoteName('#__contentitem_tag_map', 'm2') 
				." ON m2.tag_id = m.tag_id AND m2.type_alias='com_content.article' ")
				->join('INNER', $db->quoteName('#__content', 'a') 
				." on a.id = m2.content_item_id ")
				->where(
					$db->quoteName('c.alias') . ' = ' . $db->quote($this->id)
					.' AND a.state=1 and a.publish_down<=current_timestamp')
				->order(' a.publish_up desc')
				->setLimit($lim, $lim0); // limit, offset
			
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
		}		
		return $total;
	}

	function getIntroArticles() 
	{
		$db = JFactory::getDbo();

		// Find the Category
		$query = $db->getQuery(true);		
		$db->setQuery('select id, title from #__categories where alias='.$db->quote($this->id));	
		$this->category = $db->loadObject();

		if (!empty($this->category))
		{
			$query = $db->getQuery(true);		
			$db->setQuery('select count(*) from #__contentitem_tag_map '
			." where content_item_id=".$db->quote($this->category->id)
			." AND type_alias='com_content.category'"
			.' AND tag_id<>'.$db->quote( $this->params->get('tags') )
			);
			$tag_count = $this->category = $db->loadResult();
			
			
			
			// Create a new query object.
			$lim	= 7; // $app->getUserStateFromRequest("$option.limit", 'limit', 14, 'int'); //I guess getUserStateFromRequest is for session or different reasons
			$lim0	= JRequest::getVar('limitstart', 0, '', 'int');
						
			if ($tag_count>0)
			{	$total = $this->getArticlesByTags($db, $lim0, $lim);
			} else
			{	$total = $this->getAllArticles($db, $lim0, $lim);
			}
									
			// if (empty($rL)) {$jAp->enqueueMessage($db->getErrorMsg(),'error'); return;}	
			jimport('joomla.html.pagination');
			$this->items = $db->loadObjectList();
			$this->pageNav = new JPagination($total, $lim0, $lim );

		}
					
	}
	
	function getIntroItems() {
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		$query
			->select( ['c.id','c.title','c.description', 'c.alias', 'c.params' ] )
			->from($db->quoteName('#__contentitem_tag_map', 'm'))
			->join('INNER', $db->quoteName('#__categories', 'c') 
			. ' ON ' . $db->quoteName('m.content_item_id') . ' = ' . $db->quoteName('c.id'))
			->where(
				$db->quoteName('m.type_alias') . ' = ' . $db->quote('com_content.category')
				.' AND '.$db->quoteName('m.tag_id') . ' = ' . $db->quote($this->params->get('tags'))
			)
			->order($db->quoteName('c.note') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// // Load the results as a list of stdClass objects (see later for more options on retrieving data).
		
		$this->items = $db->loadObjectList();

		foreach($this->items as $img)
		{ 	$im = json_decode( $img->params );
			$imf = $im->image;			
			if ($imf!=='')
			{	$img->src = $imf;
				if ($this->params->get('resizeimage'))
				{	$img->resized = $this->retResizedImage($imf, $this->params->get('imagewidth') );
					$img->height = getimagesize($img->resized)[1];
				} else 
				{	$img->resized = $img->src;
				}
			}
		}

		// usort($this->items, function($a, $b) { 
		// 	return $a->height-$b->height;
		// });

	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = Factory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', Text::_('COM_FOTOMOTO_GALLERY_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	  
			
		$this->document->addScript(Juri::base()."media/com_categories_intro/js/categories_intro.js?v6");
		$this->document->addStyleSheet(Juri::base()."media/com_categories_intro/css/categories_intro.css?v11");
		$this->task = $app->input->get('task');
		$this->id = $app->input->get('id');
		$this->view = $app->input->get('view');

	}
}
