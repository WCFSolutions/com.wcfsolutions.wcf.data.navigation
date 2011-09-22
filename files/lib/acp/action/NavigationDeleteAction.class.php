<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/navigation/NavigationEditor.class.php');
require_once(WCF_DIR.'lib/data/navigation/item/NavigationItemEditor.class.php');

/**
 * Deletes a navigation.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.action
 * @category	Community Framework
 */
class NavigationDeleteAction extends AbstractAction {
	/**
	 * navigation id
	 * 
	 * @var integer
	 */
	public $navigationID = 0;
	
	/**
	 * navigation editor object
	 * 
	 * @var NavigationEditor
	 */
	public $navigation = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get navigation
		if (isset($_REQUEST['navigationID'])) $this->navigationID = intval($_REQUEST['navigationID']);
		$this->navigation = new NavigationEditor($this->navigationID);
	}
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// check permission
		WCF::getUser()->checkPermission('admin.navigation.canDeleteNavigation');
		
		// delete navigation
		$this->navigation->delete();
		
		// reset cache
		NavigationEditor::resetCache();
		NavigationItemEditor::resetCache();
		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=NavigationList&deletedNavigationID='.$this->navigationID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>