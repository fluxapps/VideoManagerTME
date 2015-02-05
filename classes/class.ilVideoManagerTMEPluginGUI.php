<?php
include_once("./Services/COPage/classes/class.ilPageComponentPluginGUI.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/VideoManager/classes/class.ilVideoManagerTree.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/VideoManager/classes/class.ilVideoManagerVideo.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/VideoManager/classes/Administration/class.ilVideoManagerTreeExplorerGUI.php");

/**
 * Class ilVideoManagerTMEPluginGUI
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 * @ilCtrl_isCalledBy ilVideoManagerTMEPluginGUI: ilPCPluggedGUI
 */
class ilVideoManagerTMEPluginGUI extends ilPageComponentPluginGUI{

    function executeCommand()
    {
        global $ilCtrl;

        $next_class = $ilCtrl->getNextClass();

        switch($next_class)
        {
            default:
                // perform valid commands
                $cmd = $ilCtrl->getCmd();
                if (in_array($cmd, array("create", "save", "edit", "edit2", "update", "cancel")))
                {
                    $this->$cmd();
                }
                break;
        }
    }


    /**
     * Form for new elements
     */
    function insert()
    {
        global $tpl, $ilCtrl;

        ilUtil::sendInfo($this->getPlugin()->txt('choose_video'), true);
        $tree_explorer = new ilVideoManagerTreeExplorerGUI('tree_expl', $this, 'insert', new ilVideoManagerTree(1));
        $tpl->setContent($tree_explorer->getHTML());
    }

    /**
     * Save new pc example element
     */
    public function create()
    {
        global $tpl, $lng, $ilCtrl;

        $video = new ilVideoManagerVideo($_GET['video_id']);

        $video_properties = array(
            "title" => $video->getTitle(),
            "video_src" => $video->getHttpPath().'/'.$video->getTitle(),
            "poster_src" => $video->getPosterHttp(),
            "width" => 720,
            "height" => 440,

        );

        if ($this->createElement($video_properties))
        {
            ilUtil::sendSuccess($lng->txt("msg_obj_modified"), true);
            $this->returnToParent();
        }
    }

    /**
     * Edit
     *
     * @param
     * @return
     */
    function edit()
    {
        global $tpl;

        $form = $this->initForm();
        $tpl->setContent($form->getHTML());
    }

    /**
     *
     */
    function update()
    {
        global $tpl, $lng, $ilCtrl;

        $form = $this->initForm();
        if ($form->checkInput())
        {
            $properties = $this->getProperties();
            $properties['width'] = $form->getInput('width');
            $properties['height'] = $form->getInput('height');
            if ($this->updateElement($properties))
            {
                ilUtil::sendSuccess($lng->txt("msg_obj_modified"), true);
                $this->returnToParent();
            }
        }

        $form->setValuesByPost();
        $tpl->setContent($form->getHtml());

    }


    /**
     * Init editing form
     *
     */
    public function initForm()
    {
        global $lng, $ilCtrl;

        include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
        $form = new ilPropertyFormGUI();

        // width
        $width = new ilTextInputGUI($this->getPlugin()->txt("width"), "width");
        $width->setMaxLength(4);
        $width->setSize(40);
        $width->setRequired(true);
        $form->addItem($width);

        // height
        $height = new ilTextInputGUI($this->getPlugin()->txt("height"), "height");
        $height->setMaxLength(3);
        $height->setSize(40);
        $height->setRequired(true);

        $form->addItem($height);

        $prop = $this->getProperties();
        $width->setValue($prop["width"]);
        $height->setValue($prop["height"]);

        $form->addCommandButton("update", $lng->txt("save"));
        $form->addCommandButton("cancel", $lng->txt("cancel"));
        $form->setTitle($this->getPlugin()->txt("edit_ex_el"));

        $form->setFormAction($ilCtrl->getFormAction($this));

        return $form;
    }

    /**
     * Cancel
     */
    function cancel()
    {
        $this->returnToParent();
    }

    /**
     * Get HTML for element
     *
     * @param $a_mode
     * @param array $a_properties
     * @param $a_plugin_version
     * @return mixed
     */
    function getElementHTML($a_mode, array $a_properties, $a_plugin_version)
    {
        $pl = $this->getPlugin();
        $tpl = $pl->getTemplate("tpl.content.html");

        require_once('./Services/MediaObjects/classes/class.ilPlayerUtil.php');
        ilPlayerUtil::initMediaElementJs();
        $tpl->setVariable('POSTER_SRC', $a_properties['poster_src']);
        $tpl->setVariable('VIDEO_SRC', $a_properties['video_src']);
        $tpl->setVariable('VIDEO_WIDTH', $a_properties['width']);
        $tpl->setVariable('VIDEO_HEIGHT', $a_properties['height']);

        return $tpl->get();
    }



} 