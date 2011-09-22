<?php
// wcf imports
require_once(WCF_DIR.'lib/data/navigation/item/NavigationItem.class.php');
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');

/**
 * Provides functions to manage navigation items.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	data.navigation.item
 * @category	Community Framework
 */
class NavigationItemEditor extends NavigationItem {
	/**
	 * Creates a new NavigationItemEditor object.
	 * 
	 * @param	integer		$navigationItemID
	 * @param 	array<mixed>	$row
	 * @param	Box		$cacheObject
	 * @param	boolean		$useCache
	 */
	public function __construct($navigationItemID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache) parent::__construct($navigationItemID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	wcf".WCF_N."_navigation_item
				WHERE	navigationItemID = ".$navigationItemID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}
	
	/**
	 * Updates this navigation item.
	 * 
	 * @param	integer		$navigationID
	 * @param	string		$title
	 * @param	string		$url
	 * @param	string		$icon
	 * @param	integer		$showOrder
	 * @param	integer		$languageID
	 * @param	integer		$packageID
	 */
	public function update($navigationID, $title, $url, $icon, $showOrder = 0, $languageID = 0, $packageID = PACKAGE_ID) {
		// update show order
		if ($navigationID == $this->navigationID) {
			if ($this->showOrder != $showOrder) {
				if ($showOrder < $this->showOrder) {
					$sql = "UPDATE	wcf".WCF_N."_navigation_item
						SET 	showOrder = showOrder + 1
						WHERE 	navigationID = ".$navigationID."
							AND showOrder >= ".$showOrder."
							AND showOrder < ".$this->showOrder;
					WCF::getDB()->sendQuery($sql);
				}
				else if ($showOrder > $this->showOrder) {
					$sql = "UPDATE	wcf".WCF_N."_navigation_item
						SET	showOrder = showOrder - 1
						WHERE	navigationID = ".$navigationID."
							AND showOrder <= ".$showOrder."
							AND showOrder > ".$this->showOrder;
					WCF::getDB()->sendQuery($sql);
				}
			}
		}
		else {
			$sql = "UPDATE	wcf".WCF_N."_navigation_item
				SET	showOrder = showOrder - 1
				WHERE	navigationID = ".$this->navigationID."
					AND showOrder >= ".$this->showOrder;
			WCF::getDB()->sendQuery($sql);
			
			$sql = "UPDATE	wcf".WCF_N."_navigation_item
				SET	showOrder = showOrder + 1
				WHERE	navigationID = ".$navigationID."
					AND showOrder >= ".$showOrder;
			WCF::getDB()->sendQuery($sql);			
		}
		
		// update navigation item
		$sql = "UPDATE	wcf".WCF_N."_navigation_item
			SET	navigationID = ".$navigationID.",
				".($languageID == 0 ? "navigationItem = '".escapeString($title)."'," : '')."
				url = '".escapeString($url)."',
				icon = '".escapeString($icon)."',
				showOrder = ".$showOrder."
			WHERE	navigationItemID = ".$this->navigationItemID;
		WCF::getDB()->sendQuery($sql);
		
		// update language items
		if ($languageID != 0) {
			// save language variables
			$language = new LanguageEditor($languageID);
			$language->updateItems(array('wcf.navigation.item.'.$this->navigationItem => $title), 0, PACKAGE_ID, array('wcf.navigation.item.'.$this->navigationItem => 1));
			LanguageEditor::deleteLanguageFiles($languageID, 'wcf.navigation.item', PACKAGE_ID);
		}
	}
	
	/**
	 * Updates the show order of this navigation item.
	 * 
	 * @param	integer		$showOrder
	 */
	public function updateShowOrder($showOrder) {
		$sql = "UPDATE	wcf".WCF_N."_navigation_item
			SET 	showOrder = ".$showOrder."
			WHERE 	navigationItemID = ".$this->navigationItemID;
		WCF::getDB()->sendQuery($sql);
	}
	
	/**
	 * Deletes this navigation item.
	 */
	public function delete() {
		// delete navigation item
		$sql = "DELETE FROM	wcf".WCF_N."_navigation_item
			WHERE		navigationItemID = ".$this->navigationItemID;
		WCF::getDB()->sendQuery($sql);
		
		// delete language variable
		LanguageEditor::deleteVariable('wcf.navigation.item.'.$this->navigationItem);
	}
	
	/**
	 * Creates a new navigation item.
	 * 
	 * @param	integer		$navigationID
	 * @param	string		$title
	 * @param	string		$url
	 * @param	string		$icon
	 * @param	integer		$showOrder
	 * @param	integer		$languageID
	 * @param	integer		$packageID
	 * @return	NavigationItemEditor
	 */
	public static function create($navigationID, $title, $url, $icon, $showOrder = 0, $languageID = 0, $packageID = PACKAGE_ID) {
		// get title
		$navigationItem = '';
		if ($languageID == 0) $navigationItem = $title;
		
		// get show order
		if ($showOrder == 0) {
			// get next number in row
			$sql = "SELECT	MAX(showOrder) AS showOrder
				FROM	wcf".WCF_N."_navigation_item
				WHERE	navigationID = ".$navigationID;
			$row = WCF::getDB()->getFirstRow($sql);
			if (!empty($row)) $showOrder = intval($row['showOrder']) + 1;
			else $showOrder = 1;
		}
		else {
			$sql = "UPDATE	wcf".WCF_N."_navigation_item
				SET 	showOrder = showOrder + 1
				WHERE 	navigationID = ".$navigationID."
					AND showOrder >= ".$showOrder;
			WCF::getDB()->sendQuery($sql);
		}
		
		// insert navigation item
		$sql = "INSERT INTO	wcf".WCF_N."_navigation_item
					(navigationID, navigationItem, url, icon, showOrder)
			VALUES		(".$navigationID.", '".escapeString($navigationItem)."', '".escapeString($url)."', '".escapeString($icon)."', ".$showOrder.")";
		WCF::getDB()->sendQuery($sql);
		
		// get navigation item id
		$navigationItemID = WCF::getDB()->getInsertID("wcf".WCF_N."_navigation_item", 'navigationItemID');
		
		// update language items
		if ($languageID != 0) {
			// set name
			$navigationItem = "navigationItem".$navigationItemID;
			$sql = "UPDATE	wcf".WCF_N."_navigation_item
				SET	navigationItem = '".escapeString($navigationItem)."'
				WHERE 	navigationItemID = ".$navigationItemID;
			WCF::getDB()->sendQuery($sql);
			
			// save language variables
			$language = new LanguageEditor($languageID);
			$language->updateItems(array('wcf.navigation.item.'.$navigationItem => $title));
		}
		
		// return new navigation item
		return new NavigationItemEditor($navigationItemID, null, null, false);
	}
	
	/**
	 * Resets the navigation item cache.
	 */
	public static function resetCache() {
		WCF::getCache()->addResource('navigationItem-'.PACKAGE_ID, WCF_DIR.'cache/cache.navigationItem-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderNavigationItem.class.php');
		WCF::getCache()->clearResource('navigationItem-'.PACKAGE_ID);
	}
}
?>