<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\controllers;

use vinabb\stylesdemo\includes\constants;

/**
* Controller for the extension helper
*/
class helper
{
	/** @var \phpbb\auth\auth $auth */
	protected $auth;

	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	/** @var \phpbb\log\log $log */
	protected $log;

	/** @var \phpbb\user $user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\auth\auth					$auth	Authentication object
	* @param \phpbb\db\driver\driver_interface	$db		Database object
	* @param \phpbb\log\log						$log	Log object
	* @param \phpbb\user						$user	User object
	*/
	public function __construct(\phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, \phpbb\log\log $log, \phpbb\user $user)
	{
		$this->auth = $auth;
		$this->db = $db;
		$this->log = $log;
		$this->user = $user;
	}

	/**
	* Assign ROLE_ADMIN_DEMO to the guest user
	*/
	public function set_role_admin_demo()
	{
		// Delete old roles from the guest user
		$sql = 'DELETE FROM ' . ACL_USERS_TABLE . '
			WHERE user_id = ' . ANONYMOUS;
		$this->db->sql_query($sql);

		// Insert new admin role
		$sql_ary = array(
			'user_id'			=> ANONYMOUS,
			'forum_id'			=> 0,
			'auth_option_id'	=> 0,
			'auth_role_id'		=> $this->get_demo_role_id(),
			'auth_setting'		=> 0
		);
		$this->db->sql_query('INSERT INTO ' . ACL_USERS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));

		// Clear permissions cache
		$this->auth->acl_clear_prefetch();
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACL_ADD_USER_GLOBAL_A_', time(), array(constants::ANONYMOUS_USERNAME));
	}

	/**
	* Remove ROLE_ADMIN_DEMO from the guest user
	*/
	public function unset_role_admin_demo()
	{
		$sql = 'DELETE FROM ' . ACL_USERS_TABLE . '
			WHERE user_id = ' . ANONYMOUS;
		$this->db->sql_query($sql);

		// Clear permissions cache
		$this->auth->acl_clear_prefetch();
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACL_ADD_USER_GLOBAL_A_', time(), array(constants::ANONYMOUS_USERNAME));
	}

	/**
	* Get role ID from the role name ROLE_ADMIN_DEMO
	*
	* @return int
	*/
	public function get_demo_role_id()
	{
		$sql = 'SELECT role_id
			FROM ' . ACL_ROLES_TABLE . "
			WHERE role_type = 'a_'
				AND role_name = '" . constants::ROLE_ADMIN_DEMO . "'";
		$result = $this->db->sql_query($sql);
		$role_id = (int) $this->db->sql_fetchfield('role_id');
		$this->db->sql_freeresult($result);

		return $role_id;
	}
}
