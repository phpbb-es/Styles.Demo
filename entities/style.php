<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\entities;

use vinabb\stylesdemo\includes\constants;

/**
* Entity for a single style
*/
class style extends acp_style implements style_interface
{
	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	/** @var string $root_path */
	protected $root_path;

	/** @var string $table_name */
	protected $table_name;

	/** @var string */
	protected $style_path;

	/** @var array $data */
	protected $data;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db			Database object
	* @param string								$root_path	phpBB root path
	* @param string								$table_name	Table name
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $root_path, $table_name)
	{
		$this->db = $db;
		$this->root_path = $root_path;
		$this->table_name = $table_name;

		$this->style_path = "{$this->root_path}styles/";
	}

	/**
	* Data for this entity
	*
	* @return array
	*/
	protected function prepare_data()
	{
		return [
			'style_id'				=> 'integer',
			'style_name'			=> 'string',
			'style_copyright'		=> 'string',
			'style_active'			=> 'bool',
			'style_path'			=> 'string',
			'bbcode_bitfield'		=> 'string',
			'style_parent_id'		=> 'integer',
			'style_parent_tree'		=> 'string',

			// Sub-entity: vinabb\stylesdemo\entities\sub\style_data
			'style_version'			=> 'string',
			'style_phpbb_version'	=> 'string',
			'style_author'			=> 'string',
			'style_author_url'		=> 'string',
			'style_presets'			=> 'integer',
			'style_responsive'		=> 'bool',
			'style_price'			=> 'integer',
			'style_price_label'		=> 'string',
			'style_download'		=> 'string',
			'style_mirror'			=> 'string',
			'style_details'			=> 'string',
			'style_support'			=> 'string'
		];
	}

	/**
	* Get the style's BBCode bitfield
	*
	* @return string
	*/
	public function get_bbcode_bitfield()
	{
		return isset($this->data['bbcode_bitfield']) ? (string) $this->data['bbcode_bitfield'] : '';
	}

	/**
	* Set the style's BBCode bitfield
	*
	* @param string					$text	BBCode bitfield
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_bbcode_bitfield($text)
	{
		$text = (string) $text;

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['bbcode_bitfield', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['bbcode_bitfield'] = $text;

		return $this;
	}

	/**
	* Get the parent ID
	*
	* @return int
	*/
	public function get_parent_id()
	{
		return isset($this->data['style_parent_id']) ? (int) $this->data['style_parent_id'] : 0;
	}

	/**
	* Set the parent ID
	*
	* @param int					$id		Parent ID
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_parent_id($id)
	{
		$id = (int) $id;

		// Check the existing parent style
		if ($id)
		{
			$sql = 'SELECT 1
				FROM ' . $this->table_name . "
				WHERE style_id = " . (int) $id;
			$result = $this->db->sql_query_limit($sql, 1);
			$row = $this->db->sql_fetchrow($result);
			$this->db->sql_freeresult($result);

			if ($row === false)
			{
				throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_parent_id', 'NOT_EXISTS']);
			}
		}

		// Set the value on our data array
		$this->data['style_parent_id'] = $id;

		return $this;
	}

	/**
	* Get the style's parent tree
	*
	* @return string
	*/
	public function get_parent_tree()
	{
		return isset($this->data['style_parent_tree']) ? (string) $this->data['style_parent_tree'] : '';
	}
}
