<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\entities;

/**
* Interface for a single style
*/
interface style_interface
{
	/**
	* Load the data from the database for an entity
	*
	* @param int				$id		Style ID
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function load($id = 0);

	/**
	* Import data for an entity
	*
	* Used when the data is already loaded externally.
	* Any existing data on this entity is over-written.
	* All data is validated and an exception is thrown if any data is invalid.
	*
	* @param array				$data	Data array from the database
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\invalid_argument
	*/
	public function import($data);

	/**
	* Insert the entity for the first time
	*
	* Will throw an exception if the entity was already inserted (call save() instead)
	*
	* @return style_interface $this Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function insert();

	/**
	* Save the current settings to the database
	*
	* This must be called before closing or any changes will not be saved!
	* If adding an entity (saving for the first time), you must call insert() or an exception will be thrown
	*
	* @return style_interface $this Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function save();

	/**
	* Get the style ID
	*
	* @return int
	*/
	public function get_id();

	/**
	* Get the style name
	*
	* @return string
	*/
	public function get_name();

	/**
	* Set the style name
	*
	* @param string				$text	Style name
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_name($text);

	/**
	* Get the style copyright
	*
	* @return string
	*/
	public function get_copyright();

	/**
	* Set the style copyright
	*
	* @param string				$text	Style copyright
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_copyright($text);

	/**
	* Get style's display setting
	*
	* @return bool
	*/
	public function get_active();

	/**
	* Get the style directory name
	*
	* @return string
	*/
	public function get_path();

	/**
	* Set the style directory name
	*
	* @param string				$text	Directory name
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_path($text);

	/**
	* Get the style's BBCode bitfield
	*
	* @return string
	*/
	public function get_bbcode_bitfield();

	/**
	* Set the style's BBCode bitfield
	*
	* @param string			$text	BBCode bitfield
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_bbcode_bitfield($text);

	/**
	* Get the parent ID
	*
	* @return int
	*/
	public function get_parent_id();

	/**
	* Set the parent ID
	*
	* @param int				$id		Parent ID
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_parent_id($id);

	/**
	* Get the style's parent tree
	*
	* @return string
	*/
	public function get_parent_tree();

	/**
	* Get the style version
	*
	* @return string
	*/
	public function get_version();

	/**
	* Set the style version
	*
	* @param string				$text	Style version
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_version($text);

	/**
	* Get the phpBB version
	*
	* @return string
	*/
	public function get_phpbb_version();

	/**
	* Set the phpBB version
	*
	* @param string				$text	phpBB version
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_phpbb_version($text);

	/**
	* Get the author name
	*
	* @return string
	*/
	public function get_author();

	/**
	* Set the author name
	*
	* @param string				$text	Author name
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_author($text);

	/**
	* Get the author website
	*
	* @return string
	*/
	public function get_author_url();

	/**
	* Set the author website
	*
	* @param string				$text	Author website URL
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_author_url($text);

	/**
	* Get the number of style presets
	*
	* @return int
	*/
	public function get_presets();

	/**
	* The style is responsive?
	*
	* @return bool
	*/
	public function get_responsive();

	/**
	* Get the style price
	*
	* @return int
	*/
	public function get_price();

	/**
	* Get the style price with currency
	*
	* @return string
	*/
	public function get_price_label();

	/**
	* Set the style price with currency
	*
	* @param string				$text	Style price with currency
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_price_label($text);

	/**
	* Get the style's download URL
	*
	* @return string
	*/
	public function get_download();

	/**
	* Set the style's download URL
	*
	* @param string				$text	Download URL
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_download($text);

	/**
	* Get the style's mirror URLs
	*
	* @return string
	*/
	public function get_mirror();

	/**
	* Get the style's details URL
	*
	* @return string
	*/
	public function get_details();

	/**
	* Set the style's details URL
	*
	* @param string				$text	Details URL
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_details($text);

	/**
	* Get the style's support URL
	*
	* @return string
	*/
	public function get_support();

	/**
	* Set the style's support URL
	*
	* @param string				$text	Support URL
	* @return style_interface	$this	Object for chaining calls: load()->set()->save()
	* @throws \vinabb\stylesdemo\exceptions\unexpected_value
	*/
	public function set_support($text);
}
