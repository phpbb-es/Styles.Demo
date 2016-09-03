<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\migrations;

use phpbb\db\migration\migration;

class data_modules extends migration
{
	static public function depends_on()
	{
		return array('\vinabb\stylesdemo\migrations\release_1_0_0');
	}

	public function update_data()
	{
		return array(
			// New config items
			array('config.add', array('vinabb_stylesdemo_download_direct', 1)),
			array('config.add', array('vinabb_stylesdemo_support_url', '')),
			array('config.add', array('vinabb_stylesdemo_num_acp_styles', 0, true)),

			// Style data management modules
			array('module.add', array(
				'acp',
				'ACP_CAT_STYLES_DEMO',
				array(
					'module_basename'	=> '\vinabb\stylesdemo\acp\data_module',
					'modes'				=> array('frontend', 'acp'),
				),
			)),
		);
	}
}
