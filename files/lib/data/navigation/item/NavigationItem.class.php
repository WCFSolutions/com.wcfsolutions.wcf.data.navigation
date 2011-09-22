<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a navigation item.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	data.navigation.item
 * @category	Community Framework
 */
class NavigationItem extends DatabaseObject {
	/**
	 * list of navigation items
	 * 
	 * @var	array<NavigationItem>
	 */
	protected static $navigationItems = null;
	
	/**
	 * list of page urls
	 * 
	 * @var	array
	 */
	protected static $pageURLs = null;

	/**
	 * Creates a new NavigationItem object.
	 * 
	 * @param	integer		$navigationItemID
	 * @param 	array<mixed>	$row
	 * @param	Navigation	$cacheObject
	 */
	public function __construct($navigationItemID, $row = null, $cacheObject = null) {
		if ($navigationItemID !== null) $cacheObject = self::getNavigationItem($navigationItemID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}
	
	/**
	 * Returns the title of this item.
	 * 
	 * @return	string
	 */
	public function getTitle() {
		return WCF::getLanguage()->get('wcf.navigation.item.'.$this->navigationItem);
	}
	
	/**
	 * Returns the url of this item.
	 * 
	 * @return	string
	 */
	public function getURL() {
		if (($newURL = $this->isInternalURL($this->url)) !== false) {
			if (strpos($newURL, '?') !== false) {
				$newURL .= SID_ARG_2ND_NOT_ENCODED;
			}
			else {
				$newURL .= SID_ARG_1ST; 
			}
			return $newURL;
		}
		return $this->url;
	}
	
	/**
	 * Checks whether a URL is an internal URL.
	 * 
	 * @param	string		$url
	 * @return	mixed	
	 */
	protected function isInternalURL($url) {
		if (self::$pageURLs == null) {
			self::$pageURLs = $this->getPageURLs();
		}
		
		foreach (self::$pageURLs as $pageURL) {
			if (stripos($url, $pageURL) === 0) {
				return str_ireplace($pageURL.'/', '', $url);
			}
		}
		
		return false;
	}
	
	/**
	 * Returns all navigation items of the navigation with the given navigation id.
	 * 
	 * @param	integer			$navigationID
	 * @return	array<NavigationItem>
	 */
	public static function getNavigationItemsByNavigation($navigationID) {
		WCF::getCache()->addResource('navigationItem-'.PACKAGE_ID, WCF_DIR.'cache/cache.navigationItem-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderNavigationItem.class.php');
		$cachedNavigationItemIDs = WCF::getCache()->get('navigationItem-'.PACKAGE_ID, 'navigations');
		
		$navigationItemIDs = array();
		if (isset($cachedNavigationItemIDs[$navigationID])) {
			$navigationItemIDs = $cachedNavigationItemIDs[$navigationID];
		}
		
		return array_map(array('NavigationItem', 'getNavigationItem'), $navigationItemIDs);
	}
	
	/**
	 * Returns a list of all navigation items.
	 * 
	 * @return 	array<NavigationItem>
	 */
	public static function getNavigationItems() {
		if (self::$navigationItems == null) {
			WCF::getCache()->addResource('navigationItem-'.PACKAGE_ID, WCF_DIR.'cache/cache.navigationItem-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderNavigationItem.class.php');
			self::$navigationItems = WCF::getCache()->get('navigationItem-'.PACKAGE_ID, 'navigationItems');
		}
		
		return self::$navigationItems;
	}
	
	/**
	 * Returns the navigation item with the given navigation item id from cache.
	 * 
	 * @param 	integer		$navigationItemID
	 * @return	NavigationItem
	 */
	public static function getNavigationItem($navigationItemID) {
		$navigationItems = self::getNavigationItems();
		
		if (!isset($navigationItems[$navigationItemID])) {
			throw new IllegalLinkException();
		}
		
		return $navigationItems[$navigationItemID];
	}
	
	/**
	 * Returns the page URLs.
	 * 
	 * @return	array
	 */
	public static function getPageURLs() {
		if (self::$pageURLs === null) {
			$urlString = '';
			if (defined('PAGE_URL')) $urlString .= PAGE_URL;
			if (defined('PAGE_URLS')) $urlString .= "\n".PAGE_URLS;
			
			$urlString = StringUtil::unifyNewlines($urlString);
			self::$pageURLs = ArrayUtil::trim(explode("\n", $urlString));
		}
		return self::$pageURLs;
	}
}
?>