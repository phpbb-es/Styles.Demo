<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\acp;

/**
* ACP module: acp_settings
*/
class settings_module
{
	/** @var \vinabb\stylesdemo\controllers\acp\settings $controller */
	protected $controller;

	/** @var \phpbb\user $user */
	protected $user;

	/** @var string $tpl_name */
	public $tpl_name;

	/** @var string $page_title */
	public $page_title;

	/** @var string $u_action */
	public $u_action;

	/**
	* Main method of the module
	*
	* @param string	$id		Module basename
	* @param string	$mode	Module mode
	*/
	public function main($id, $mode)
	{
		global $phpbb_container;

		$this->user = $phpbb_container->get('user');
		$this->controller = $phpbb_container->get('vinabb.stylesdemo.acp.settings');

		// ACP template file
		$this->tpl_name = 'settings_body';
		$this->page_title = $this->user->lang('ACP_STYLES_DEMO');

		// Language
		$this->user->add_lang_ext('vinabb/stylesdemo', 'acp_styles_demo');

		// Display settings
		$this->controller->set_form_action($this->u_action);
		$this->controller->display_settings();
	}
}
