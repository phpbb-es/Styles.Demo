<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\operators;

/**
* Interface for a set of ACP styles
*/
interface acp_style_interface
{
	/**
	* Get number of styles
	*
	* @param bool $only_active True to count only active styles, false to count all
	* @return int
	*/
	public function count_styles($only_active = true);

	/**
	* Get all styles
	*
	* @param bool $only_active True to get only active styles, false to get all
	* @return array
	*/
	public function get_styles($only_active = true);

	/**
	* Add a style
	*
	* @param \vinabb\stylesdemo\entities\acp_style_interface $entity ACP style entity
	* @return \vinabb\stylesdemo\entities\acp_style_interface
	*/
	public function add_style(\vinabb\stylesdemo\entities\acp_style_interface $entity);

	/**
	* Delete a style
	*
	* @param int $id Style ID
	* @return bool True if row was deleted, false otherwise
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function delete_style($id);
}
