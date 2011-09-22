<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/navigation/NavigationEditor.class.php');

/**
 * Shows the navigation add form.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.form
 * @category	Community Framework
 */
class NavigationAddForm extends ACPForm {
	// system
	public $templateName = 'navigationAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.navigation.add';
	public $neededPermissions = 'admin.navigation.canAddNavigation';
	
	/**
	 * navigation editor object
	 * 
	 * @var	NavigationEditor
	 */
	public $navigation = null;
	
	// parameters
	public $title = '';
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// title
		if (empty($this->title)) {
			throw new UserInputException('title');
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save navigation item
		$this->navigation = NavigationEditor::create($this->title);
		
		// reset cache
		NavigationEditor::resetCache();
		$this->saved();
		
		// reset values
		$this->title = '';
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'action' => 'add',
			'title' => $this->title
		));
	}
}
?>