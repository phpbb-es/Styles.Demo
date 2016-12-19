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
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'styles'	=> $this->get_schema_styles()
			),
			'add_tables'	=> array(
				$this->table_prefix . 'acp_styles'	=> $this->get_schema_acp_styles()
			)
		);
	}

	/**
	* Get extra schema for the table: _styles
	*
	* @return array
	*/
	protected function get_schema_styles()
	{
		return array(
			'style_version'			=> array('VCHAR:20', ''),
			'style_phpbb_version'	=> array('VCHAR:20', ''),
			'style_author'			=> array('VCHAR_UNI', ''),
			'style_author_url'		=> array('VCHAR', ''),
			'style_presets'			=> array('UINT', 0),
			'style_responsive'		=> array('BOOL', 0),
			'style_price'			=> array('BINT', 0),
			'style_price_label'		=> array('VCHAR_UNI', ''),
			'style_download'		=> array('VCHAR', ''),
			'style_mirror'			=> array('TEXT', null),
			'style_details'			=> array('VCHAR', ''),
			'style_support'			=> array('VCHAR', '')
		);
	}

	/**
	* Get schema for the table: _acp_styles
	*
	* @return array
	*/
	protected function get_schema_acp_styles()
	{
		return array(
			'COLUMNS'	=> array(
				'style_id'				=> array('UINT', null, 'auto_increment'),
				'style_name'			=> array('VCHAR_UNI:255', ''),
				'style_copyright'		=> array('VCHAR_UNI', ''),
				'style_active'			=> array('BOOL', 1),
				'style_path'			=> array('VCHAR:100', ''),
				'style_version'			=> array('VCHAR:20', ''),
				'style_phpbb_version'	=> array('VCHAR:20', ''),
				'style_author'			=> array('VCHAR_UNI', ''),
				'style_author_url'		=> array('VCHAR', ''),
				'style_presets'			=> array('UINT', 0),
				'style_responsive'		=> array('BOOL', 0),
				'style_price'			=> array('BINT', 0),
				'style_price_label'		=> array('VCHAR_UNI', ''),
				'style_download'		=> array('VCHAR', ''),
				'style_mirror'			=> array('TEXT', null),
				'style_details'			=> array('VCHAR', ''),
				'style_support'			=> array('VCHAR', '')
			),
			'PRIMARY_KEY'	=> 'style_id',
			'KEYS'	=> array(
				'style_name'	=> array('UNIQUE', 'style_name')
			)
		);
	}

	/**
	* Revert schema
	*
	* @return array
	*/
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'styles'	=> array(
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
				)
			),
			'drop_tables'	=> array($this->table_prefix . 'acp_styles')
		);
	}
}
