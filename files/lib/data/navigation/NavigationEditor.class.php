<?php
// wcf imports
require_once(WCF_DIR.'lib/data/navigation/Navigation.class.php');

/**
 * Provides functions to manage navigations.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-201 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	data.navigation
 * @category	Community Framework
 */
class NavigationEditor extends Navigation {
	/**
	 * Creates a new NavigationEditor object.
	 * 
	 * @param	integer		$navigationID
	 * @param 	array<mixed>	$row
	 * @param	Box		$cacheObject
	 * @param	boolean		$useCache
	 */
	public function __construct($navigationID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($navigationID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_navigation
				WHERE	navigationID = ".$navigationID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}
	
	/**
	 * Updates this navigation.
	 * 
	 * @param	string		$title
	 */
	public function update($title) {
		$sql = "UPDATE	wcf".WCF_N."_navigation
			SET	title = '".escapeString($title)."'
			WHERE	navigationID = ".$this->navigationID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this navigation.
	 */
	public function delete() {	
		// delete navigation items
		$sql = "DELETE FROM	wcf".WCF_N."_navigation_item
			WHERE		navigationID = ".$this->navigationID;
		WCF::getDB()->sendQuery($sql);
		
		// delete navigation
		$sql = "DELETE FROM	wcf".WCF_N."_navigation
			WHERE		navigationID = ".$this->navigationID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Creates a new navigation.
	 * 
	 * @param	string		$title
	 * @param	integer		$packageID
	 * @return	NavigationEditor
	 */
	public static function create($title, $packageID = PACKAGE_ID) {
		// insert navigation
		$sql = "INSERT INTO	wcf".WCF_N."_navigation
					(packageID, title)
			VALUES		(".$packageID.", '".escapeString($title)."')";
		WCF::getDB()->sendQuery($sql);
		
		$navigationID = WCF::getDB()->getInsertID("wcf".WCF_N."_navigation", 'navigationID');
		return new NavigationEditor($navigationID, null, null, false);
	}
	
	/**
	 * Resets the navigation cache.
	 */
	public static function resetCache() {
		WCF::getCache()->addResource('navigation-'.PACKAGE_ID, WCF_DIR.'cache/cache.navigation-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderNavigation.class.php');
		WCF::getCache()->clearResource('navigation-'.PACKAGE_ID);
	}
}
?>