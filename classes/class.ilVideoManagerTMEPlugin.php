<?php
include_once("./Services/COPage/classes/class.ilPageComponentPlugin.php");

/**
 * Class ilVideoManagerTMEPlugin
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ilVideoManagerTMEPlugin extends ilPageComponentPlugin{

	/**
	 * @var ilRbacSystem
	 */
	protected $rbacreview;
	/**
	 * @var ilObjUser
	 */
	protected $user;

	public function __construct() {
		parent::__construct();

		global $DIC;

		$this->rbacreview = $DIC->rbac()->review();
		$this->user = $DIC->user();
	}


	/**
     * Get plugin name
     *
     * @return string
     */
    function getPluginName()
    {
        return "VideoManagerTME";
    }


    /**
     * Get plugin name
     *
     * @return string
     */
    function isValidParentType($a_parent_type)
    {
        if($roles = $this->rbacreview->getRolesByFilter(2, 0, 'VideoManagerTME')) {
            foreach($roles as $role) {
                if($this->rbacreview->isAssigned($this->user->getId(), $role['rol_id'])){
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    /**
     * Get Javascript files
     */
    function getJavascriptFiles($a_mode)
    {
//        return array("js/pcexp.js");
	    return array();
    }

    /**
     * Get css files
     */
    function getCssFiles($a_mode)
    {
//        return array("css/pcexp.css");
	    return array();
    }

    protected function beforeUninstall() {
	    // Nothing to delete
	   return true;
    }
} 

