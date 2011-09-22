<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
require_once(WCF_DIR.'lib/data/navigation/item/NavigationItem.class.php');

/**
 * Represents a navigation.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	data.navigation
 * @category	Community Framework
 */
class Navigation extends DatabaseObject {
	/**
	 * list of navigation items
	 * 
	 * @var	array<NavigationItem>
	 */
	protected $navigationItems = null;
	
	/**
	 * list of navigations
	 * 
	 * @var	array<Navigation>
	 */
	protected static $navigations = null;
	
	/**
	 * Creates a new Navigation object.
	 * 
	 * @param	integer		$navigationID
	 * @param 	array<mixed>	$row
	 * @param	Navigation	$cacheObject
	 */
	public function __construct($navigationID, $row = null, $cacheObject = null) {
		if ($navigationID !== null) $cacheObject = self::getNavigation($navigationID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}
	
	/**
	 * Returns the title of this navigation.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return $this->title;
	}
	
	/**
	 * Returns a list of navigation items of this navigation.
	 * 
	 * @return	array<NavigationItem>
	 */
	public function getNavigationItems() {
		if ($this->navigationItems === null) {
			$this->navigationItems = NavigationItem::getNavigationItemsByNavigation($this->navigationID);
		}
		
		return $this->navigationItems;
	}
	
	/**
	 * Returns a list of all navigations.
	 * 
	 * @return 	array<Navigation>
	 */
	public static function getNavigations() {
		if (self::$navigations == null) {
			WCF::getCache()->addResource('navigation-'.PACKAGE_ID, WCF_DIR.'cache/cache.navigation-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderNavigation.class.php');
			self::$navigations = WCF::getCache()->get('navigation-'.PACKAGE_ID);
		}
		
		return self::$navigations;
	}
	
	/**
	 * Returns the navigation with the given navigation id from cache.
	 * 
	 * @param 	integer		$navigationID
	 * @return	Navigation
	 */
	public static function getNavigation($navigationID) {
		$navigations = self::getNavigations();
		
		if (!isset($navigations[$navigationID])) {
			throw new IllegalLinkException();
		}
		
		return $navigations[$navigationID];
	}
}
?>