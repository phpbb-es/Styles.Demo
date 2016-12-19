<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\entities;

use vinabb\stylesdemo\entities\sub\style_data;
use vinabb\stylesdemo\includes\constants;

/**
* Entity for a single ACP style
*/
class acp_style extends style_data implements acp_style_interface
{
	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	/** @var string $table_name */
	protected $table_name;

	/** @var string */
	protected $ext_real_path;

	/** @var string */
	protected $style_path;

	/** @var array $data */
	protected $data;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface	$db				Database object
	* @param string								$table_name		Table name
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db, $table_name)
	{
		$this->db = $db;
		$this->table_name = $table_name;

		$this->ext_real_path = dirname(__DIR__) . '/';
		$this->style_path = "{$this->ext_real_path}app/styles/";
	}

	/**
	* Data for this entity
	*
	* @return array
	*/
	protected function prepare_data()
	{
		return array(
			'style_id'				=> 'integer',
			'style_name'			=> 'string',
			'style_copyright'		=> 'string',
			'style_active'			=> 'bool',
			'style_path'			=> 'string',

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
		);
	}

	/**
	* Load the data from the database for an entity
	*
	* @param int					$id		Style ID
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function load($id = 0)
	{
		$sql = 'SELECT *
			FROM ' . $this->table_name . '
			WHERE style_id = ' . (int) $id;
		$result = $this->db->sql_query($sql);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		// The entity does not exist
		if ($this->data === false)
		{
			throw new \vinabb\stylesdemo\exceptions\out_of_bounds('style_id');
		}

		return $this;
	}

	/**
	* Import data for an entity
	*
	* Used when the data is already loaded externally.
	* Any existing data on this entity is over-written.
	* All data is validated and an exception is thrown if any data is invalid.
	*
	* @param array					$data	Data array from the database
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\invalid_argument
	*/
	public function import($data)
	{
		// Clear out any saved data
		$this->data = array();

		// Go through the basic fields and set them to our data array
		foreach ($this->prepare_data() as $field => $type)
		{
			// The data wasn't sent to us
			if (!isset($data[$field]))
			{
				throw new \vinabb\stylesdemo\exceptions\invalid_argument([$field, 'EMPTY']);
			}

			// settype() passes values by reference
			$value = $data[$field];

			// We're using settype() to enforce data types
			settype($value, $type);

			$this->data[$field] = $value;
		}

		return $this;
	}

	/**
	* Insert the entity for the first time
	*
	* Will throw an exception if the entity was already inserted (call save() instead)
	*
	* @return acp_style_interface $this Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function insert()
	{
		// The entity already exists
		if (!empty($this->data['style_id']))
		{
			throw new \vinabb\stylesdemo\exceptions\out_of_bounds('style_id');
		}

		// Make extra sure there is no ID set
		unset($this->data['style_id']);

		$sql = 'INSERT INTO ' . $this->table_name . ' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);

		// Set the ID using the ID created by the SQL INSERT
		$this->data['style_id'] = (int) $this->db->sql_nextid();

		return $this;
	}

	/**
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding an entity (saving for the first time), you must call insert() or an exception will be thrown
	*
	* @return acp_style_interface $this Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function save()
	{
		// The entity does not exist
		if (empty($this->data['style_id']))
		{
			throw new \vinabb\stylesdemo\exceptions\out_of_bounds('style_id');
		}

		// Copy the data array, filtering out the ID
		// so we do not attempt to update the row's identity column.
		$sql_array = array_diff_key($this->data, ['style_id' => null]);

		$sql = 'UPDATE ' . $this->table_name . '
			SET ' . $this->db->sql_build_array('UPDATE', $sql_array) . '
			WHERE style_id = ' . $this->get_id();
		$this->db->sql_query($sql);

		return $this;
	}

	/**
	* Get the style ID
	*
	* @return int
	*/
	public function get_id()
	{
		return isset($this->data['style_id']) ? (int) $this->data['style_id'] : 0;
	}

	/**
	* Get the style name
	*
	* @return string
	*/
	public function get_name()
	{
		return isset($this->data['style_name']) ? (string) $this->data['style_name'] : '';
	}

	/**
	* Set the style name
	*
	* @param string					$text	Style name
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_name($text)
	{
		$text = (string) $text;

		// This is a required field
		if ($text == '')
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_name', 'EMPTY']);
		}

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_name', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_name'] = $text;

		return $this;
	}

	/**
	* Get the style copyright
	*
	* @return string
	*/
	public function get_copyright()
	{
		return isset($this->data['style_copyright']) ? (string) $this->data['style_copyright'] : '';
	}

	/**
	* Set the style copyright
	*
	* @param string					$text	Style copyright
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_copyright($text)
	{
		$text = (string) $text;

		// Check the max length
		if (utf8_strlen($text) > constants::MAX_CONFIG_NAME)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_copyright', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_copyright'] = $text;

		return $this;
	}

	/**
	* Get style's display setting
	*
	* @return bool
	*/
	public function get_active()
	{
		return isset($this->data['style_active']) ? (bool) $this->data['style_active'] : true;
	}

	/**
	* Get the style directory name
	*
	* @return string
	*/
	public function get_path()
	{
		return isset($this->data['style_path']) ? (string) $this->data['style_path'] : '';
	}

	/**
	* Set the style directory name
	*
	* @param string					$text	Directory name
	* @return acp_style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_path($text)
	{
		$text = (string) $text;

		// This is a required field
		if ($text == '')
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_path', 'EMPTY']);
		}

		// Check the existing path
		$dir = "{$this->style_path}{$text}/";

		if (!file_exists($dir) || !is_dir($dir))
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_path', 'NOT_EXISTS']);
		}

		// Check the max length
		if (utf8_strlen($text) > 100)
		{
			throw new \vinabb\stylesdemo\exceptions\unexpected_value(['style_path', 'TOO_LONG']);
		}

		// Set the value on our data array
		$this->data['style_path'] = $text;

		return $this;
	}
}
