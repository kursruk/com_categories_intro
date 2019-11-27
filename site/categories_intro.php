<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Categories_intro
 * @author     kursruk <kursruk@gmail.com>
 * @copyright  2019 kursruk
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Categories_intro', JPATH_COMPONENT);
JLoader::register('Categories_introController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Categories_intro');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
