<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\acp;

class data_info
{
	public function module()
	{
		return [
			'filename'		=> '\vinabb\stylesdemo\acp\data_module',
			'title'			=> 'ACP_MANAGE_STYLE_DATA',
			'version'		=> '1.0.0',
			'modes'			=> [
				'frontend'	=> [
					'title'	=> 'ACP_MANAGE_STYLE_DATA',
					'auth'	=> 'ext_vinabb/stylesdemo && acl_a_board',
					'cat'	=> ['ACP_CAT_STYLES_DEMO']
				],
				'acp'		=> [
					'title'	=> 'ACP_MANAGE_ACP_STYLE_DATA',
					'auth'	=> 'ext_vinabb/stylesdemo && acl_a_board',
					'cat'	=> ['ACP_CAT_STYLES_DEMO']
				],
			]
		];
	}
}
