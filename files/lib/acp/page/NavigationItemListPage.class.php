<?php
// wcf imports
require_once(WCF_DIR.'lib/data/navigation/Navigation.class.php');
require_once(WCF_DIR.'lib/data/navigation/item/NavigationItemList.class.php');
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * Shows a list of all navigation items.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.page
 * @category	Community Framework
 */
class NavigationItemListPage extends SortablePage {
	// system
	public $templateName = 'navigationItemList';
	public $defaultSortField = 'showOrder';
	public $neededPermissions = array('admin.navigation.canEditNavigationItem', 'admin.navigation.canDeleteNavigationItem');
	
	/**
	 * deleted navigation item id
	 * 
	 * @var	integer
	 */
	public $deletedNavigationItemID = 0;
	
	/**
	 * navigation item list object
	 * 
	 * @var	NavigationItemList
	 */
	public $navigationItemList = null;
			
	/**
	 * navigation id
	 * 
	 * @var	integer
	 */
	public $navigationID = 0;
	
	/**
	 * navigation editor object
	 * 
	 * @var	NavigationEditor
	 */
	public $navigation = null;
	
	/**
	 * list of navigations
	 * 
	 * @var	array<Navigation>
	 */
	public $navigations = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedNavigationItemID'])) $this->deletedNavigationItemID = intval($_REQUEST['deletedNavigationItemID']);
		
		// get navigation
		if (isset($_REQUEST['navigationID'])) $this->navigationID = intval($_REQUEST['navigationID']);
		if ($this->navigationID) {
			$this->navigation = new Navigation($this->navigationID);
		}
		
		// init navigation item list
		if ($this->navigation !== null) {
			$this->navigationItemList = new NavigationItemList();
			$this->navigationItemList->sqlConditions = 'navigation_item.navigationID = '.$this->navigation->navigationID;
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function readData() {
		parent::readData();
		
		// read navigation items
		if ($this->navigationItemList !== null) {
			$this->navigationItemList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
			$this->navigationItemList->sqlLimit = $this->itemsPerPage;
			$this->navigationItemList->sqlOrderBy = 'navigation_item.'.$this->sortField." ".$this->sortOrder;
			$this->navigationItemList->readObjects();
		}
		
		// get navigations
		$this->navigations = Navigation::getNavigations();
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'navigationItemID':
			case 'navigationItem':
			case 'showOrder': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		if ($this->navigationItemList === null) return 0;
		return $this->navigationItemList->countObjects();
	}
		
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'navigationItems' => ($this->navigationItemList !== null ? $this->navigationItemList->getObjects() : array()),
			'navigationID' => $this->navigationID,
			'navigation' => $this->navigation,
			'navigations' => $this->navigations,
			'deletedNavigationItemID' => $this->deletedNavigationItemID
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.navigation.item.view');
		
		parent::show();
	}
}
?>