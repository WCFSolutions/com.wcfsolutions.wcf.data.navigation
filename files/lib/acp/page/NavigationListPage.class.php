<?php
// wcf imports
require_once(WCF_DIR.'lib/data/navigation/NavigationList.class.php');
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * Shows a list of all navigations.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.page
 * @category	Community Framework
 */
class NavigationListPage extends SortablePage {
	// system
	public $templateName = 'navigationList';
	public $defaultSortField = 'navigationID';
	public $neededPermissions = array('admin.navigation.canEditNavigation', 'admin.navigation.canDeleteNavigation');
	
	/**
	 * deleted navigation id
	 * 
	 * @var	integer
	 */
	public $deletedNavigationID = 0;
	
	/**
	 * navigation list object
	 * 
	 * @var	NavigationList
	 */
	public $navigationList = null;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['deletedNavigationID'])) $this->deletedNavigationID = intval($_REQUEST['deletedNavigationID']);
		
		// init navigation list
		$this->navigationList = new NavigationList();
		$this->navigationList->sqlConditions = "navigation.packageID IN (
							SELECT	dependency
							FROM	wcf".WCF_N."_package_dependency
							WHERE	packageID = ".PACKAGE_ID."
						)";
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function readData() {
		parent::readData();
		
		// read navigations
		$this->navigationList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->navigationList->sqlLimit = $this->itemsPerPage;
		$this->navigationList->sqlOrderBy = ($this->sortField != 'navigationItems' ? 'navigation.' : '').$this->sortField." ".$this->sortOrder;
		$this->navigationList->readObjects();
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'navigationID':
			case 'title':
			case 'navigationItems': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->navigationList->countObjects();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'navigations' => $this->navigationList->getObjects(),
			'deletedNavigationID' => $this->deletedNavigationID
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.navigation.view');
		
		parent::show();
	}
}
?>