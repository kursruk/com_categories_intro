<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Categories_intro
 * @author     kursruk <kursruk@gmail.com>
 * @copyright  2019 kursruk
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_categories_intro'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Categories_intro', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Categories_introHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'categories_intro.php');

$controller = BaseController::getInstance('Categories_intro');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
