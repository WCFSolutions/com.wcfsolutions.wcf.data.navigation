<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches all navigations and their navigation items.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	system.cache
 * @category	Community Framework
 */
class CacheBuilderNavigation implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();
		
		// get navigation ids
		$navigationIDArray = array();
		$sql = "SELECT		navigationID 
			FROM		wcf".WCF_N."_navigation navigation,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		navigation.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$navigationIDArray[] = $row['navigationID'];
		}
		
		if (count($navigationIDArray) > 0) {
			require_once(WCF_DIR.'lib/data/navigation/Navigation.class.php');
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_navigation
				WHERE	navigationID IN (".implode(',', $navigationIDArray).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$data[$row['navigationID']] = new Navigation(null, $row);
			}
		}
		
		return $data;
	}
}
?>