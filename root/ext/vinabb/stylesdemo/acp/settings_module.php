<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\acp;

use vinabb\stylesdemo\includes\constants;

class settings_module
{
	/** @var string */
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container, $phpEx;

		$this->config = $phpbb_container->get('config');
		$this->db = $phpbb_container->get('dbal.conn');
		$this->log = $phpbb_container->get('log');
		$this->request = $phpbb_container->get('request');
		$this->template = $phpbb_container->get('template');
		$this->user = $phpbb_container->get('user');
		$this->auth = $phpbb_container->get('auth');

		$this->tpl_name = 'settings_body';
		$this->page_title = $this->user->lang('ACP_STYLES_DEMO');
		$this->real_path = dirname(__DIR__) . '/';
		$this->user->add_lang_ext('vinabb/stylesdemo', 'acp_styles_demo');

		add_form_key('vinabb/stylesdemo');

		$errors = array();

		// Submit
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('vinabb/stylesdemo'))
			{
				$errors[] = $this->user->lang('FORM_INVALID');
			}

			// Get from the form
			$logo_text = $this->request->variable('logo_text', '');
			$lang_enable = $this->request->variable('lang_enable', false);
			$lang_switch = $this->request->variable('lang_switch', '');
			$acp_enable = $this->request->variable('acp_enable', false);
			$json_enable = $this->request->variable('json_enable', false);
			$json_url = $this->request->variable('json_url', '');
			$screenshot_type = $this->request->variable('screenshot_type', constants::SCREENSHOT_TYPE_LOCAL);

			// Check switch lang
			if ($lang_enable && (empty($lang_switch) || $lang_switch == $this->config['default_lang']))
			{
				$lang_enable = false;
				$lang_switch = '';
			}

			// Check JSON URL
			if ($json_enable && empty($json_url))
			{
				$json_enable = false;
			}

			// Check PhantomJS
			if ($screenshot_type == constants::SCREENSHOT_TYPE_PHANTOM)
			{
				if (!file_exists("{$this->real_path}bin/"))
				{
					$errors[] = $this->user->lang('ERROR_PHANTOM_NOT_FOUND', constants::EXT_PATH_IN_LANG . 'bin/');
				}
				else if (!is_writable("{$this->real_path}bin/") || !is_executable("{$this->real_path}bin/"))
				{
					$errors[] = $this->user->lang('ERROR_PHANTOM_NOT_CHMOD', constants::EXT_PATH_IN_LANG . 'bin/');
				}
			}

			if (empty($errors))
			{
				// Assign the role ROLE_ADMIN_DEMO to the guest user if ACP mode is enabled
				if ($acp_enable && !$this->config['vinabb_stylesdemo_acp_enable'])
				{
					$sql_ary = array(
						'user_id'			=> ANONYMOUS,
						'forum_id'			=> 0,
						'auth_option_id'	=> 0,
						'auth_role_id'		=> $this->get_demo_role_id(),
						'auth_setting'		=> 0
					);
			
					$this->db->sql_query('INSERT INTO ' . ACL_USERS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
				}
				else if (!$acp_enable && $this->config['vinabb_stylesdemo_acp_enable'])
				{
					$sql = 'DELETE FROM ' . ACL_USERS_TABLE . ' WHERE user_id = ' . ANONYMOUS . ' AND auth_role_id = ' . $this->get_demo_role_id();
					$this->db->sql_query($sql);
				}

				// Clear permissions cache
				$this->auth->acl_clear_prefetch();

				$this->config->set('vinabb_stylesdemo_logo_text', $logo_text);
				$this->config->set('vinabb_stylesdemo_lang_enable', $lang_enable);
				$this->config->set('vinabb_stylesdemo_lang_switch', $lang_switch);
				$this->config->set('vinabb_stylesdemo_acp_enable', $acp_enable);
				$this->config->set('vinabb_stylesdemo_json_enable', $json_enable);
				$this->config->set('vinabb_stylesdemo_json_url', $json_url);
				$this->config->set('vinabb_stylesdemo_screenshot_type', $screenshot_type);

				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_STYLES_DEMO_SETTINGS');
				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACL_ADD_USER_GLOBAL_A_', time(), array('Anonymous'));

				trigger_error($this->user->lang('STYLES_DEMO_SETTINGS_UPDATED') . adm_back_link($this->u_action));
			}
			else
			{
				trigger_error(implode('<br>', $errors) . adm_back_link($this->u_action), E_USER_WARNING);				
			}
		}

		// Select an extra language to switch
		$sql = 'SELECT *
			FROM ' . LANG_TABLE . '
			ORDER BY lang_english_name';
		$result = $this->db->sql_query($sql);
		$rows = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		$selected_lang_switch = isset($lang_switch) ? $lang_switch : $this->config['vinabb_stylesdemo_lang_switch'];
		$default_lang_name = $lang_switch_options = '';

		if (sizeof($rows))
		{
			if (sizeof($rows) > 1)
			{
				$lang_switch_options .= '<option value=""' . (($selected_lang_switch == '') ? ' selected' : '' ) . '>' . $this->user->lang('SELECT_LANGUAGE') . '</option>';
			}

			foreach ($rows as $row)
			{
				if ($row['lang_iso'] == $this->config['default_lang'])
				{
					$default_lang_name = ($row['lang_english_name'] == $row['lang_local_name']) ? $row['lang_english_name'] : $row['lang_english_name'] . ' (' . $row['lang_local_name'] . ')';
				}
				else
				{
					$lang_switch_options .= '<option value="' . $row['lang_iso'] . '"' . (($selected_lang_switch == $row['lang_iso']) ? ' selected' : '' ) . '>' . $row['lang_english_name'] . ' (' . $row['lang_local_name'] . ')</option>';
				}
			}
		}

		// Output
		$this->template->assign_vars(array(
			'STYLES_DEMO_URL'	=> generate_board_url() . (($this->config['enable_mod_rewrite']) ? '' : "/app.$phpEx") . '/demo/',

			'LOGO_TEXT'			=> isset($logo_text) ? $logo_text : $this->config['vinabb_stylesdemo_logo_text'],
			'DEFAULT_LANG'		=> $default_lang_name,
			'LANG_ENABLE'		=> isset($lang_enable) ? $lang_enable : $this->config['vinabb_stylesdemo_lang_enable'],
			'ACP_ENABLE'		=> isset($acp_enable) ? $acp_enable : $this->config['vinabb_stylesdemo_acp_enable'],
			'JSON_ENABLE'		=> isset($json_enable) ? $json_enable : $this->config['vinabb_stylesdemo_json_enable'],
			'JSON_URL'			=> (isset($json_url) && !empty($json_url)) ? $json_url : $this->config['vinabb_stylesdemo_json_url'],

			'SCREENSHOT_TYPE'			=> isset($screenshot_type) ? $screenshot_type : $this->config['vinabb_stylesdemo_screenshot_type'],
			'SCREENSHOT_TYPE_LOCAL'		=> constants::SCREENSHOT_TYPE_LOCAL,
			'SCREENSHOT_TYPE_JSON'		=> constants::SCREENSHOT_TYPE_JSON,
			'SCREENSHOT_TYPE_PHANTOM'	=> constants::SCREENSHOT_TYPE_PHANTOM,
			'OS_NAME'					=> $this->get_php_os_name(),
			'GET_PHANTOM_FOR_OS'		=> $this->user->lang('GET_PHANTOM_' . ((PHP_INT_SIZE === 4 && $this->get_php_os_name(true) == 'LINUX') ? 'LINUX_32' : $this->get_php_os_name(true)), constants::PHANTOM_URL, constants::EXT_PATH_IN_LANG),
			'GET_PHANTOM_NO_OS'			=> $this->user->lang('GET_PHANTOM_NO_OS', constants::PHANTOM_URL, constants::EXT_PATH_IN_LANG),

			'LANG_SWITCH_OPTIONS'	=> $lang_switch_options,

			'U_ACTION'	=> $this->u_action,
		));
	}

	/**
	* Get role_id by the role_name ROLE_ADMIN_DEMO
	*
	* @return int The role ID
	*/
	protected function get_demo_role_id()
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

	/**
	* Get server OS name
	*
	* @param bool $get_lang_key false returns OS name, true returns lang key for OS
	* @return string OS name, empty if undefined
	*/
	protected function get_php_os_name($get_lang_key = false)
	{
		switch (strtoupper(PHP_OS))
		{
			case 'WINNT';
				return ($get_lang_key) ? 'WIN' : 'Windows';
			break;

			case 'DARWIN';
				return ($get_lang_key) ? 'MAC' : 'Mac OS X';
			break;

			case 'LINUX';
				return ($get_lang_key) ? 'LINUX' : 'Linux';
			break;

			case 'FREEBSD':
				return ($get_lang_key) ? 'BSD' : 'FreeBSD';
			break;

			default:
				return '';
			break;
		}
	}
}
