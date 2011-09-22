<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/NavigationAddForm.class.php');

/**
 * Shows the navigation edit form.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.form
 * @category	Community Framework
 */
class NavigationEditForm extends NavigationAddForm {
	// system
	public $activeMenuItem = 'wcf.acp.menu.link.navigation';
	public $neededPermissions = 'admin.navigation.canEditNavigation';
	
	/**
	 * navigation id
	 * 
	 * @var	integer
	 */
	public $navigationID = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get navigation
		if (isset($_REQUEST['navigationID'])) $this->navigationID = intval($_REQUEST['navigationID']);
		$this->navigation = new NavigationEditor($this->navigationID);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			// get values
			$this->title = $this->navigation->title;
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// update navigation
		$this->navigation->update($this->title);
		
		// reset cache
		NavigationEditor::resetCache();
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'action' => 'edit',
			'navigationID' => $this->navigationID,
			'navigation' => $this->navigation
		));
	}
}
?>