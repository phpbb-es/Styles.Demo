<?php
/**
* This file is part of the VinaBB Demo Styles package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\demostyles\acp;

class settings_info
{
	public function module()
	{
		return array(
			'filename'		=> '\vinabb\demostyles\acp\settings_module',
			'title'			=> 'ACP_DEMO_STYLES',
			'version'		=> '1.0.0',
			'modes'			=> array(
				'settings'	=> array(
					'title'	=> 'ACP_DEMO_STYLES',
					'auth'	=> 'ext_vinabb/demostyles && acl_a_board && acl_a_extensions',
					'cat'	=> array('ACP_CAT_DEMO_STYLES'),
				),
			),
		);
	}
}
