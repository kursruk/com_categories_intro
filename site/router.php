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

JLoader::registerPrefix('Categories_intro', JPATH_SITE . '/components/com_categories_intro/');

/**
 * Class Categories_introRouter
 *
 * @since  3.3
 */
class Categories_introRouter extends \Joomla\CMS\Component\Router\RouterBase
{
	/**
	 * Build method for URLs
	 * This method is meant to transform the query parameters into a more human
	 * readable form. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();
		$view     = null;


		if (isset($query['view']))
		{	if ($query['view']!=='categories')
			{	$segments[] = $query['view'];				
			}
			$view = $query['view'];				
			unset($query['view']);
		}

		if (isset($query['id']))
		{
			if ($view !== null)
			{
				$segments[] = $query['id'];
			}
			else
			{
				$segments[] = $query['id'];
			}

			unset($query['id']);
		}

		if (isset($query['task']))
		{
			$taskParts  = explode('.', $query['task']);
			$segments[] = implode('/', $taskParts);
			$view       = $taskParts[0];
			unset($query['task']);
		}

		return $segments;
	}

	/**
	 * Parse method for URLs
	 * This method is meant to transform the human readable URL back into
	 * query parameters. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		$vars = array();

		// View is always the first element of the array
		$vars['view'] = 'categories';
		$vars['id'] = array_shift($segments);
		$vars['task'] = array_shift($segments);

		return $vars;
	}
}
