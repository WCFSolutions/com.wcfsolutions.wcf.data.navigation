<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/action/AbstractNavigationItemAction.class.php');

/**
 * Deletes a navigation item.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.action
 * @category	Community Framework
 */
class NavigationItemDeleteAction extends AbstractNavigationItemAction {	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.navigation.canDeleteNavigationItem');
		
		// delete navigation item
		$this->navigationItem->delete();
		
		// reset cache
		NavigationItemEditor::resetCache();
		NavigationEditor::resetCache();
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=NavigationItemList&navigationID='.$this->navigationItem->navigationID.'&deletedNavigationItemID='.$this->navigationItemID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>