<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/NavigationItemAddForm.class.php');

/**
 * Shows the navigation item edit form.
 * 
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.form
 * @category	Community Framework
 */
class NavigationItemEditForm extends NavigationItemAddForm {
	// system
	public $activeMenuItem = 'wcf.acp.menu.link.navigation.item';
	public $neededPermissions = 'admin.navigation.canEditNavigationItem';
	
	/**
	 * navigation item id
	 * 
	 * @var	integer
	 */
	public $navigationItemID = 0;
	
	/**
	 * language id
	 * 
	 * @var	integer
	 */
	public $languageID = 0;
	
	/**
	 * list of available languages
	 *
	 * @var	array
	 */
	public $languages = array();
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// get language id
		if (isset($_REQUEST['languageID'])) $this->languageID = intval($_REQUEST['languageID']);
		else $this->languageID = WCF::getLanguage()->getLanguageID();
		
		// get navigation item
		if (isset($_REQUEST['navigationItemID'])) $this->navigationItemID = intval($_REQUEST['navigationItemID']);
		$this->navigationItem = new NavigationItemEditor($this->navigationItemID);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		// get all available languages
		$this->languages = Language::getLanguageCodes();
		
		if (!count($_POST)) {
			// get values
			$this->navigationID = $this->navigationItem->navigationID;
			$this->url = $this->navigationItem->url;
			$this->icon = $this->navigationItem->icon;
			$this->showOrder = $this->navigationItem->showOrder;
			
			// get title and description
			if (WCF::getLanguage()->getLanguageID() != $this->languageID) $language = new Language($this->languageID);
			else $language = WCF::getLanguage();			
			$this->title = $language->get('wcf.navigation.item.'.$this->navigationItem->navigationItem);
			if ($this->title == 'wcf.navigation.item.'.$this->navigationItem->navigationItem) $this->title = '';
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		AbstractForm::save();
		
		// update navigation item
		$this->navigationItem->update($this->navigationID, $this->title, $this->url, $this->icon, $this->showOrder, $this->languageID);
		
		// reset cache
		NavigationItemEditor::resetCache();
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
			'navigationItemID' => $this->navigationItemID,
			'languageID' => $this->languageID,
			'languages' => $this->languages
		));
	}
}
?>