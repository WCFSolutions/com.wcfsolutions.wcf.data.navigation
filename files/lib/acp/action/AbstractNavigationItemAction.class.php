<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/navigation/NavigationEditor.class.php');
require_once(WCF_DIR.'lib/data/navigation/item/NavigationItemEditor.class.php');

/**
 * Provides default implementations for navigation item actions.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.action
 * @category	Community Framework
 */
abstract class AbstractNavigationItemAction extends AbstractAction {
	/**
	 * navigation item id
	 * 
	 * @var integer
	 */
	public $navigationItemID = 0;
	
	/**
	 * navigation item editor object
	 * 
	 * @var NavigationItemEditor
	 */
	public $navigationItem = null;
	
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get navigation item
		if (isset($_REQUEST['navigationItemID'])) $this->navigationItemID = intval($_REQUEST['navigationItemID']);
		$this->navigationItem = new NavigationItemEditor($this->navigationItemID);
	}
}
?>