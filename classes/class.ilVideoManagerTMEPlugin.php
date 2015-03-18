<?php
include_once("./Services/COPage/classes/class.ilPageComponentPlugin.php");

/**
 * Class ilVideoManagerTMEPlugin
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ilVideoManagerTMEPlugin extends ilPageComponentPlugin {

	/**
	 * Get plugin name
	 *
	 * @return string
	 */
	public function getPluginName() {
		return "VideoManagerTME";
	}


	/**
	 * Get plugin name
	 *
	 * @return string
	 */
	public function isValidParentType($a_parent_type) {
		return true;
	}


	/**
	 * Get Javascript files
	 */
	public function getJavascriptFiles() {
		//        return array("js/pcexp.js");
	}


	/**
	 * Get css files
	 */
	public function getCssFiles() {
		//        return array("css/pcexp.css");
	}
}