<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\migrations;

use phpbb\db\migration\migration;

/**
* Migration: styles_table
* To do:	Add new columns for the existing table _styles
*			Add the new table _acp_styles
*/
class styles_table extends migration
{
	/**
	* Update schema
	*
	* @return array
	*/
	public function update_schema()
	{
		return [
			'add_columns'	=> [
				$this->table_prefix . 'styles'	=> $this->get_schema_styles()
			],
			'add_tables'	=> [
				$this->table_prefix . 'acp_styles'	=> $this->get_schema_acp_styles()
			]
		];
	}

	/**
	* Get extra schema for the table: _styles
	*
	* @return array
	*/
	protected function get_schema_styles()
	{
		return [
			'style_version'			=> ['VCHAR:20', ''],
			'style_phpbb_version'	=> ['VCHAR:20', ''],
			'style_author'			=> ['VCHAR_UNI', ''],
			'style_author_url'		=> ['VCHAR', ''],
			'style_presets'			=> ['UINT', 0],
			'style_responsive'		=> ['BOOL', 0],
			'style_price'			=> ['BINT', 0],
			'style_price_label'		=> ['VCHAR_UNI', ''],
			'style_download'		=> ['VCHAR', ''],
			'style_mirror'			=> ['TEXT', null],
			'style_details'			=> ['VCHAR', ''],
			'style_support'			=> ['VCHAR', '']
		];
	}

	/**
	* Get schema for the table: _acp_styles
	*
	* @return array
	*/
	protected function get_schema_acp_styles()
	{
		return [
			'COLUMNS'	=> [
				'style_id'				=> ['UINT', null, 'auto_increment'],
				'style_name'			=> ['VCHAR_UNI:255', ''],
				'style_copyright'		=> ['VCHAR_UNI', ''],
				'style_active'			=> ['BOOL', 1],
				'style_path'			=> ['VCHAR:100', ''],
				'style_version'			=> ['VCHAR:20', ''],
				'style_phpbb_version'	=> ['VCHAR:20', ''],
				'style_author'			=> ['VCHAR_UNI', ''],
				'style_author_url'		=> ['VCHAR', ''],
				'style_presets'			=> ['UINT', 0],
				'style_responsive'		=> ['BOOL', 0],
				'style_price'			=> ['BINT', 0],
				'style_price_label'		=> ['VCHAR_UNI', ''],
				'style_download'		=> ['VCHAR', ''],
				'style_mirror'			=> ['TEXT', null],
				'style_details'			=> ['VCHAR', ''],
				'style_support'			=> ['VCHAR', '']
			],
			'PRIMARY_KEY'	=> 'style_id',
			'KEYS'	=> [
				'style_name'	=> ['UNIQUE', 'style_name']
			]
		];
	}

	/**
	* Revert schema
	*
	* @return array
	*/
	public function revert_schema()
	{
		return [
			'drop_columns'	=> [
				$this->table_prefix . 'styles'	=> [
					'style_version',
					'style_phpbb_version',
					'style_author',
					'style_author_url',
					'style_presets',
					'style_responsive',
					'style_price',
					'style_price_label',
					'style_download',
					'style_mirror',
					'style_details',
					'style_support'
				]
			],
			'drop_tables'	=> [$this->table_prefix . 'acp_styles']
		];
	}
}
