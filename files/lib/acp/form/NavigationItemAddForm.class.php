<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/data/navigation/NavigationEditor.class.php');
require_once(WCF_DIR.'lib/data/navigation/item/NavigationItemEditor.class.php');

/**
 * Shows the navigation item add form.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.form
 * @category	Community Framework
 */
class NavigationItemAddForm extends ACPForm {
	// system
	public $templateName = 'navigationItemAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.navigation.item.add';
	public $neededPermissions = 'admin.navigation.canAddNavigationItem';
	
	/**
	 * navigation editor object
	 * 
	 * @var	NavigationEditor
	 */
	public $navigation = null;
	
	/**
	 * navigation item editor object
	 * 
	 * @var	NavigationItemEditor
	 */
	public $navigationItem = null;
	
	// parameters
	public $navigationID = 0;
	public $title = '';
	public $url = '';
	public $icon = '';
	public $showOrder = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get navigation id
		if (isset($_REQUEST['navigationID'])) $this->navigationID = intval($_REQUEST['navigationID']);
	}
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['title'])) $this->title = StringUtil::trim($_POST['title']);
		if (isset($_POST['url'])) $this->url = StringUtil::trim($_POST['url']);
		if (isset($_POST['icon'])) $this->icon = StringUtil::trim($_POST['icon']);
		if (isset($_POST['showOrder'])) $this->showOrder = intval($_POST['showOrder']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		// navigation id
		try {
			Navigation::getNavigation($this->navigationID);
		}
		catch (IllegalLinkException $e) {
			throw new UserInputException('navigationID');
		}
		
		// title
		if (empty($this->title)) {
			throw new UserInputException('title');
		}
		
		// url
		if (empty($this->url)) {
			throw new UserInputException('url');
		}
		
		// icon
		/*if (empty($this->icon)) {
			throw new UserInputException('icon');
		}*/
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		// save navigation item
		$this->navigationItem = NavigationItemEditor::create($this->navigationID, $this->title, $this->url, $this->icon, $this->showOrder, WCF::getLanguage()->getLanguageID());
		
		// reset cache
		NavigationItemEditor::resetCache();
		$this->saved();
		
		// reset values
		$this->title = $this->url = $this->icon = '';
		$this->showOrder = 0;
		
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
			'navigationID' => $this->navigationID,
			'title' => $this->title,
			'url' => $this->url,
			'icon' => $this->icon,
			'showOrder' => $this->showOrder,
			'navigations' => Navigation::getNavigations()
		));
	}
}
?>