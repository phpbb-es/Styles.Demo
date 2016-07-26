<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo;

use vinabb\stylesdemo\includes\constants;

class ext extends \phpbb\extension\base
{
	/**
	* Overwrite enable_step to assign the role ROLE_ADMIN_DEMO to the guest user
	* before the extension is enabled.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	* @access public
	*/
	public function enable_step($old_state)
	{
		$config = $this->container->get('config');

		if (isset($config['vinabb_stylesdemo_acp_enable']) && $config['vinabb_stylesdemo_acp_enable'])
		{
			$this->set_role_admin_demo();
		}

		return parent::enable_step($old_state);
	}

	/**
	* Overwrite disable_step to unassign the role ROLE_ADMIN_DEMO to the guest user
	* before the extension is disabled.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	* @access public
	*/
	public function disable_step($old_state)
	{
		$this->unset_role_admin_demo();

		return parent::disable_step($old_state);
	}

	/**
	* Assign ROLE_ADMIN_DEMO to the guest user
	*/
	protected function set_role_admin_demo()
	{
		$db = $this->container->get('dbal.conn');
		$user = $this->container->get('user');
		$auth = $this->container->get('auth');
		$log = $this->container->get('log');

		// Delete old roles from the guest user
		$sql = 'DELETE FROM ' . ACL_USERS_TABLE . ' WHERE user_id = ' . ANONYMOUS;
		$db->sql_query($sql);

		// Insert new admin role
		$sql_ary = array(
			'user_id'			=> ANONYMOUS,
			'forum_id'			=> 0,
			'auth_option_id'	=> 0,
			'auth_role_id'		=> $this->get_demo_role_id(),
			'auth_setting'		=> 0
		);

		$db->sql_query('INSERT INTO ' . ACL_USERS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));

		// Clear permissions cache
		$auth->acl_clear_prefetch();

		$log->add('admin', $user->data['user_id'], $user->ip, 'LOG_ACL_ADD_USER_GLOBAL_A_', time(), array('Anonymous'));
	}

	/**
	* Remove ROLE_ADMIN_DEMO from the guest user
	*/
	protected function unset_role_admin_demo()
	{
		$db = $this->container->get('dbal.conn');
		$user = $this->container->get('user');
		$auth = $this->container->get('auth');
		$log = $this->container->get('log');

		$sql = 'DELETE FROM ' . ACL_USERS_TABLE . ' WHERE user_id = ' . ANONYMOUS;
		$db->sql_query($sql);

		// Clear permissions cache
		$auth->acl_clear_prefetch();

		$log->add('admin', $user->data['user_id'], $user->ip, 'LOG_ACL_ADD_USER_GLOBAL_A_', time(), array('Anonymous'));
	}

	/**
	* Get role_id by the role_name ROLE_ADMIN_DEMO
	*
	* @return int The role ID
	*/
	protected function get_demo_role_id()
	{
		$db = $this->container->get('dbal.conn');

		$sql = 'SELECT role_id
			FROM ' . ACL_ROLES_TABLE . "
			WHERE role_type = 'a_'
				AND role_name = '" . constants::ROLE_ADMIN_DEMO . "'";
		$result = $db->sql_query($sql);
		$role_id = (int) $db->sql_fetchfield('role_id');
		$db->sql_freeresult($result);

		return $role_id;
	}
}
