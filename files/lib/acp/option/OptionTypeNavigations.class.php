<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/option/OptionTypeRadiobuttons.class.php');

/**
 * OptionTypeSelect is an implementation of OptionType for a navigation select.
 *
 * @author	Sebastian Oettl
 * @copyright	2009-2011 WCF Solutions <http://www.wcfsolutions.com/index.html>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.wcfsolutions.wcf.data.navigation
 * @subpackage	acp.option
 * @category	Community Framework
 */
class OptionTypeNavigations implements OptionType {
	protected $navigations = null;
	
	/**
	 * @see OptionType::getFormElement()
	 */
	public function getFormElement(&$optionData) {
		if (!isset($optionData['optionValue'])) {
			if (isset($optionData['defaultValue'])) $optionData['optionValue'] = $optionData['defaultValue'];
			else $optionData['optionValue'] = false;
		}
		
		// read navigations
		$this->readNavigations();
		
		WCF::getTPL()->assign(array(
			'optionData' => $optionData,
			'options' => $this->navigations
		));
		return WCF::getTPL()->fetch('optionTypeSelect');
	}
	
	/**
	 * @see OptionType::validate()
	 */
	public function validate($optionData, $newValue) {
		$this->readNavigations();
		if (!isset($this->navigations[$newValue])) {
			throw new UserInputException($optionData['optionName'], 'validationFailed');
		}
	}
	
	/**
	 * @see OptionType::getData()
	 */
	public function getData($optionData, $newValue) {
		return $newValue;
	}
	
	/**
	 * Reads the navigations.
	 */
	protected function readNavigations() {
		if ($this->navigations === null) {
			require_once(WCF_DIR.'lib/data/navigation/Navigation.class.php');
			$this->navigations = Navigation::getNavigations();
		}
	}
}
?>