<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\controller;

/**
* Interface for the extension helper
*/
interface helper_interface
{
	/**
	* Assign ROLE_ADMIN_DEMO to the guest user
	*/
	public function set_role_admin_demo();

	/**
	* Remove ROLE_ADMIN_DEMO from the guest user
	*/
	public function unset_role_admin_demo();

	/**
	* Get role ID from the role name ROLE_ADMIN_DEMO
	*
	* @return int
	*/
	public function get_demo_role_id();
}
