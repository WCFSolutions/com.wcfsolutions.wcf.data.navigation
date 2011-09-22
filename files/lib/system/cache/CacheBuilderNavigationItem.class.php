<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches all navigation items.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	system.cache
 * @category	Community Framework
 */
class CacheBuilderNavigationItem implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array('navigationItems' => array(), 'navigations' => array());
		
		// get navigation item ids
		$navigationItemIDArray = array();
		$sql = "SELECT		navigation_item.navigationItemID 
			FROM		wcf".WCF_N."_navigation_item navigation_item,
					wcf".WCF_N."_navigation navigation,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		navigation_item.navigationID = navigation.navigationID
					AND navigation.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$navigationItemIDArray[] = $row['navigationItemID'];
		}
		
		if (count($navigationItemIDArray) > 0) {
			require_once(WCF_DIR.'lib/data/navigation/item/NavigationItem.class.php');
			$sql = "SELECT		*
				FROM		wcf".WCF_N."_navigation_item
				WHERE		navigationItemID IN (".implode(',', $navigationItemIDArray).")
				ORDER BY	showOrder ASC";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				if (!isset($data['navigations'][$row['navigationID']])) {
					$data['navigations'][$row['navigationID']] = array();
				}
				$data['navigations'][$row['navigationID']][] = $row['navigationItemID'];
				$data['navigationItems'][$row['navigationItemID']] = new NavigationItem(null, $row);
			}
		}
		
		return $data;
	}
}
?>