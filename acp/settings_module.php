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

	/** @var \phpbb\language\language $language */
	protected $language;

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

		$this->language = $phpbb_container->get('language');
		$this->controller = $phpbb_container->get('vinabb.stylesdemo.acp.settings');

		// ACP template file
		$this->tpl_name = 'settings_body';
		$this->page_title = $this->language->lang('ACP_STYLES_DEMO');

		// Language
		$this->language->add_lang('acp_styles_demo', 'vinabb/stylesdemo');

		// Display settings
		$this->controller->set_form_action($this->u_action);
		$this->controller->display_settings();
	}
}
