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
		return [
			'filename'		=> '\vinabb\stylesdemo\acp\settings_module',
			'title'			=> 'ACP_STYLES_DEMO',
			'version'		=> '1.0.0',
			'modes'			=> [
				'settings'	=> [
					'title'	=> 'ACP_STYLES_DEMO',
					'auth'	=> 'ext_vinabb/stylesdemo && acl_a_board',
					'cat'	=> ['ACP_CAT_STYLES_DEMO']
				],
			]
		];
	}
}
