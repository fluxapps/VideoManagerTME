<?php
include_once("./Services/COPage/classes/class.ilPageComponentPluginGUI.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/VideoManager/classes/class.ilVideoManagerTree.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/VideoManager/classes/class.ilVideoManagerVideo.php");
include_once("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/VideoManager/classes/Administration/class.ilVideoManagerTreeExplorerGUI.php");

/**
 * Class ilVideoManagerTMEPluginGUI
 *
 * @author            Theodor Truffer <tt@studer-raimann.ch>
 * @ilCtrl_isCalledBy ilVideoManagerTMEPluginGUI: ilPCPluggedGUI
 */
class ilVideoManagerTMEPluginGUI extends ilPageComponentPluginGUI {

	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;

	function __construct() {
		parent::__construct();

		global $DIC;

		$this->ctrl = $DIC->ctrl();
		$this->tpl = $DIC->ui()->mainTemplate();
	}


	public function executeCommand() {
		$next_class = $this->ctrl->getNextClass();

		switch ($next_class) {
			default:
				// perform valid commands
				$cmd = $this->ctrl->getCmd();
				if (in_array($cmd, array( "create", "save", "edit", "edit2", "update", "cancel" ))) {
					$this->$cmd();
				}
				break;
		}
	}


	public function insert() {
		ilUtil::sendInfo($this->getPlugin()->txt('choose_video'), true);
		$tree_explorer = new ilVideoManagerTreeExplorerGUI('tree_expl', $this, 'insert', new ilVideoManagerTree(1));
		$this->tpl->setContent($tree_explorer->getHTML());
	}


	public function create() {
		$video = new ilVideoManagerVideo($_GET['video_id']);

		$video_properties = array(
			"title" => $video->getTitle(),
			"video_src" => $video->getHttpPath() . '/' . $video->getTitle(),
			"poster_src" => $video->getPosterHttp(),
			"width" => $video->getWidth(),
			"height" => $video->getHeight(),

		);

		if ($this->createElement($video_properties)) {
			ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
			$this->returnToParent();
		}
	}


	public function edit() {
		$form = $this->initForm();
		$this->tpl->setContent($form->getHTML());
	}


	public function update() {
		$form = $this->initForm();
		if ($form->checkInput()) {
			$properties = $this->getProperties();
			$properties['width'] = $form->getInput('width');
			$properties['height'] = $form->getInput('height');
			if ($this->updateElement($properties)) {
				ilUtil::sendSuccess($this->lng->txt("msg_obj_modified"), true);
				$this->returnToParent();
			}
		}

		$form->setValuesByPost();
		$this->tpl->setContent($form->getHtml());
	}


	/**
	 * @return ilPropertyFormGUI
	 */
	public function initForm() {
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

		$form->addCommandButton("update", $this->lng->txt("save"));
		$form->addCommandButton("cancel", $this->lng->txt("cancel"));
		$form->setTitle($this->getPlugin()->txt("edit_ex_el"));

		$form->setFormAction($this->ctrl->getFormAction($this));

		return $form;
	}


	public function cancel() {
		$this->returnToParent();
	}


	/**
	 * Get HTML for element
	 *
	 * @param       $a_mode
	 * @param array $a_properties
	 * @param       $a_plugin_version
	 *
	 * @return mixed
	 */
	public function getElementHTML($a_mode, array $a_properties, $a_plugin_version) {
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