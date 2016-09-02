<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\migrations;

use phpbb\db\migration\migration;

class styles_table extends migration
{
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'styles'	=> array(
					'style_version'			=> array('VCHAR:20', ''),
					'style_phpbb_version'	=> array('VCHAR:20', ''),
					'style_author'			=> array('VCHAR_UNI', ''),
					'style_author_url'		=> array('VCHAR', ''),
					'style_presets'			=> array('UINT', 0),
					'style_responsive'		=> array('BOOL', 0),
					'style_price'			=> array('BINT', 0),
					'style_price_label'		=> array('VCHAR', ''),
					'style_download'		=> array('VCHAR', ''),
					'style_mirror'			=> array('TEXT', ''),
					'style_details'			=> array('VCHAR', ''),
					'style_support'			=> array('VCHAR', ''),
				),
			),
		);
	}
}
