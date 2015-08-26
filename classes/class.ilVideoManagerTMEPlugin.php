<?php
include_once("./Services/COPage/classes/class.ilPageComponentPlugin.php");

/**
 * Class ilVideoManagerTMEPlugin
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class ilVideoManagerTMEPlugin extends ilPageComponentPlugin{

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
        global $ilUser, $rbacreview;
        if($roles = $rbacreview->getRolesByFilter(2, 0, 'VideoManagerTME')) {
            foreach($roles as $role) {
                if($rbacreview->isAssigned($ilUser->getId(), $role['rol_id'])){
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
    function getJavascriptFiles()
    {
//        return array("js/pcexp.js");
    }

    /**
     * Get css files
     */
    function getCssFiles()
    {
//        return array("css/pcexp.css");
    }

} 

