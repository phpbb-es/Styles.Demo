<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\acp;

class settings_info
{
	public function module()
	{
		return array(
			'filename'		=> '\vinabb\stylesdemo\acp\settings_module',
			'title'			=> 'ACP_STYLES_DEMO',
			'version'		=> '1.0.0',
			'modes'			=> array(
				'settings'	=> array(
					'title'	=> 'ACP_STYLES_DEMO',
					'auth'	=> 'ext_vinabb/stylesdemo && acl_a_board && acl_a_extensions',
					'cat'	=> array('ACP_CAT_STYLES_DEMO'),
				),
			),
		);
	}
}
