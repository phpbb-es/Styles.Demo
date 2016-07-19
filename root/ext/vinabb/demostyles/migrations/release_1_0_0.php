<?php
/**
* This file is part of the VinaBB Demo Styles package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\demostyles\migrations;

use phpbb\db\migration\migration;

class release_1_0_0 extends migration
{
	public function effectively_installed()
	{
		return isset($this->config['vinabb_demostyles_lang_enable']);
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320a1');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('vinabb_demostyles_lang_enable', 0)),
			array('config.add', array('vinabb_demostyles_lang_switch', '')),
			array('config.add', array('vinabb_demostyles_acp_enable', 0)),
			array('config.add', array('vinabb_demostyles_json_enable', 0)),
			array('config.add', array('vinabb_demostyles_json_url', '')),

			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_CAT_DEMO_STYLES'
			)),

			array('module.add', array(
				'acp',
				'ACP_CAT_DEMO_STYLES',
				array(
					'module_basename'	=> '\vinabb\demostyles\acp\settings_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
