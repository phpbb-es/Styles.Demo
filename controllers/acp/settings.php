<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\controllers\acp;

use vinabb\stylesdemo\includes\constants;

class settings
{
	/** @var string */
	protected $u_action;

	/**
	* Set form action URL
	*
	* @param string $u_action Form action
	*/
	public function set_form_action($u_action)
	{
		$this->u_action = $u_action;
	}

	/**
	* Extension settings
	*/
	public function display_settings()
	{
		global $phpbb_container;

		$this->auth = $phpbb_container->get('auth');
		$this->config = $phpbb_container->get('config');
		$this->db = $phpbb_container->get('dbal.conn');
		$this->ext_manager = $phpbb_container->get('ext.manager');
		$this->filesystem = $phpbb_container->get('filesystem');
		$this->language = $phpbb_container->get('language');
		$this->log = $phpbb_container->get('log');
		$this->request = $phpbb_container->get('request');
		$this->template = $phpbb_container->get('template');
		$this->user = $phpbb_container->get('user');
		$this->helper = $phpbb_container->get('controller.helper');

		$this->tpl_name = 'settings_body';
		$this->page_title = $this->language->lang('ACP_STYLES_DEMO');
		$this->ext_root_path = $this->ext_manager->get_extension_path('vinabb/stylesdemo', true);
		$this->language->add_lang('acp_styles_demo', 'vinabb/stylesdemo');

		add_form_key('vinabb/stylesdemo');

		$errors = [];

		// Resolution data
		$resolutions = [
			800		=> ['lang' => 'SVGA', 'height' => 600],
			1024	=> ['lang' => 'XGA', 'height' => 768],
			1280	=> ['lang' => 'WXGA', 'height' => 768],
			1366	=> ['lang' => 'HD', 'height' => 768],
			1440	=> ['lang' => 'WXGA_PLUS', 'height' => 900],
			1600	=> ['lang' => 'HD_PLUS', 'height' => 900],
			1920	=> ['lang' => 'FHD', 'height' => 1080],
			2560	=> ['lang' => 'QHD', 'height' => 1440],
			3440	=> ['lang' => 'WQHD', 'height' => 1440],
			3840	=> ['lang' => 'UHD', 'height' => 2160],
			5120	=> ['lang' => '5K', 'height' => 2880],
			7680	=> ['lang' => '8K', 'height' => 4320]
		];

		// Submit
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('vinabb/stylesdemo'))
			{
				$errors[] = $this->language->lang('FORM_INVALID');
			}

			// Get from the form
			$logo_text = $this->request->variable('logo_text', '', true);
			$auto_toggle = $this->request->variable('auto_toggle', true);
			$download_direct = $this->request->variable('download_direct', true);
			$phone_width = max(constants::MIN_PHONE_WIDTH, $this->request->variable('phone_width', 0));
			$tablet_width = max($phone_width + constants::MIN_PHONE_WIDTH, $this->request->variable('tablet_width', 0));
			$lang_enable = $this->request->variable('lang_enable', false);
			$lang_switch = $this->request->variable('lang_switch', '');
			$acp_enable = $this->request->variable('acp_enable', false);
			$json_enable = $this->request->variable('json_enable', false);
			$json_url = $this->request->variable('json_url', '');
			$support_url = $this->request->variable('support_url', '');
			$screenshot_type = $this->request->variable('screenshot_type', constants::SCREENSHOT_TYPE_LOCAL);
			$screenshot_width = max(constants::MIN_SCREEN_WIDTH, $this->request->variable('screenshot_width', 0));
			$screenshot_height = isset($resolutions[$screenshot_width]['height']) ? $resolutions[$screenshot_width]['height'] : constants::MIN_SCREEN_HEIGHT;

			// Check switch lang
			if ($lang_enable && (empty($lang_switch) || $lang_switch == $this->config['default_lang']))
			{
				$lang_enable = false;
				$lang_switch = '';
			}

			// Check JSON URL
			if ($json_enable)
			{
				if (empty($json_url))
				{
					$json_enable = false;
				}
				// Test file URL
				else
				{
					if (filter_var($json_url, FILTER_VALIDATE_URL))
					{
						$test = get_headers($json_url);

						if (strpos($test[0], '200') === false)
						{
							$json_enable = false;
							$errors[] = $this->language->lang('ERROR_JSON_URL_NOT_RESPONSE');
						}
					}
					else
					{
						$json_enable = false;
						$errors[] = $this->language->lang('ERROR_JSON_URL_NOT_VALID');
					}
				}
			}

			// Check PhantomJS
			if ($screenshot_type == constants::SCREENSHOT_TYPE_PHANTOM)
			{
				$phantomjs_filename = ($this->get_php_os_name(true) == 'WIN') ? 'phantomjs.exe' : 'phantomjs';

				if (!$this->filesystem->exists("{$this->ext_root_path}bin/"))
				{
					$errors[] = $this->language->lang('ERROR_PHANTOM_NOT_FOUND', "{$this->ext_root_path}bin/");
				}
				else
				{
					if (!$this->filesystem->is_writable("{$this->ext_root_path}bin/"))
					{
						$errors[] = $this->language->lang('ERROR_PHANTOM_NOT_WRITE', "{$this->ext_root_path}bin/");
					}
					else
					{
						if (!$this->filesystem->exists("{$this->ext_root_path}bin/images/"))
						{
							$this->filesystem->mkdir("{$this->ext_root_path}bin/images/");
						}

						if (!$this->filesystem->exists("{$this->ext_root_path}bin/js/"))
						{
							$this->filesystem->mkdir("{$this->ext_root_path}bin/js/");
						}
					}

					if (!is_executable("{$this->ext_root_path}bin/{$phantomjs_filename}"))
					{
						$errors[] = $this->language->lang('ERROR_PHANTOM_NOT_EXEC', "{$this->ext_root_path}bin/{$phantomjs_filename}");
					}
				}
			}

			if (empty($errors))
			{
				// Assign the role ROLE_ADMIN_DEMO to the guest user if ACP mode is enabled
				if ($acp_enable && !$this->config['vinabb_stylesdemo_acp_enable'])
				{
					// Delete old roles from the guest user
					$sql = 'DELETE FROM ' . ACL_USERS_TABLE . ' WHERE user_id = ' . ANONYMOUS;
					$this->db->sql_query($sql);

					// Insert new admin role
					$sql_ary = [
						'user_id'			=> ANONYMOUS,
						'forum_id'			=> 0,
						'auth_option_id'	=> 0,
						'auth_role_id'		=> $this->get_demo_role_id(),
						'auth_setting'		=> 0
					];

					$this->db->sql_query('INSERT INTO ' . ACL_USERS_TABLE . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
				}
				else if (!$acp_enable && $this->config['vinabb_stylesdemo_acp_enable'])
				{
					$sql = 'DELETE FROM ' . ACL_USERS_TABLE . ' WHERE user_id = ' . ANONYMOUS;
					$this->db->sql_query($sql);
				}

				if ($acp_enable != $this->config['vinabb_stylesdemo_acp_enable'])
				{
					// Clear permissions cache
					$this->auth->acl_clear_prefetch();

					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACL_ADD_USER_GLOBAL_A_', time(), ['Anonymous']);
				}

				// Delete all screenshots which created by PhantomJS but with old resolution setting
				if ($screenshot_type == constants::SCREENSHOT_TYPE_PHANTOM && $screenshot_width != $this->config['vinabb_stylesdemo_screenshot_width'])
				{
					$scan_dirs = ['images'	=> constants::SCREENSHOT_EXT, 'js' => '.js'];

					foreach ($scan_dirs as $scan_dir => $file_ext)
					{
						$suffix = '_' . $this->config['vinabb_stylesdemo_screenshot_width'] . 'x' . $this->config['vinabb_stylesdemo_screenshot_height'] . $file_ext;

						if ($this->filesystem->exists("{$this->ext_root_path}bin/{$scan_dir}/"))
						{
							$scan_files = array_diff(scandir("{$this->ext_root_path}bin/{$scan_dir}/"), ['..', '.', '.htaccess']);

							foreach ($scan_files as $scan_file)
							{
								if (substr($scan_file, strlen($suffix) * -1) == $suffix)
								{
									$this->filesystem->remove("{$this->ext_root_path}bin/{$scan_dir}/{$scan_file}");
								}
							}
						}
					}
				}

				$this->config->set('vinabb_stylesdemo_logo_text', $logo_text);
				$this->config->set('vinabb_stylesdemo_auto_toggle', $auto_toggle);
				$this->config->set('vinabb_stylesdemo_download_direct', $download_direct);
				$this->config->set('vinabb_stylesdemo_phone_width',$phone_width);
				$this->config->set('vinabb_stylesdemo_tablet_width', $tablet_width);
				$this->config->set('vinabb_stylesdemo_lang_enable', $lang_enable);
				$this->config->set('vinabb_stylesdemo_lang_switch', $lang_switch);
				$this->config->set('vinabb_stylesdemo_acp_enable', $acp_enable);
				$this->config->set('vinabb_stylesdemo_json_enable', $json_enable);
				$this->config->set('vinabb_stylesdemo_json_url', $json_url);
				$this->config->set('vinabb_stylesdemo_support_url', $support_url);
				$this->config->set('vinabb_stylesdemo_screenshot_type', $screenshot_type);
				$this->config->set('vinabb_stylesdemo_screenshot_width', $screenshot_width);
				$this->config->set('vinabb_stylesdemo_screenshot_height', $screenshot_height);

				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_STYLES_DEMO_SETTINGS');

				trigger_error($this->language->lang('STYLES_DEMO_SETTINGS_UPDATED') . adm_back_link($this->u_action));
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
				$lang_switch_options .= '<option value=""' . (($selected_lang_switch == '') ? ' selected' : '' ) . '>' . $this->language->lang('SELECT_LANGUAGE') . '</option>';
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

		// Select custom screenshot resolution
		$selected_screenshot_width = isset($screenshot_width) ? $screenshot_width : $this->config['vinabb_stylesdemo_screenshot_width'];
		$resolution_options = '<option value="0"' . (($selected_screenshot_width == 0) ? ' selected' : '' ) . '>' . $this->language->lang('SELECT_RESOLUTION') . '</option>';

		foreach ($resolutions as $resolution_width => $data)
		{
			$resolution_options .= '<option value="' . $resolution_width . '"' . (($selected_screenshot_width == $resolution_width) ? ' selected' : '' ) . '>' . $this->language->lang('RESOLUTION_' . $data['lang'], $resolution_width, $data['height']) . '</option>';
		}

		// Output
		$this->template->assign_vars([
			'STYLES_DEMO_URL'	=> $this->helper->route('vinabb_stylesdemo_route', ['mode' => '']),

			'LOGO_TEXT'			=> isset($logo_text) ? $logo_text : $this->config['vinabb_stylesdemo_logo_text'],
			'AUTO_TOGGLE'		=> isset($auto_toggle) ? $auto_toggle : $this->config['vinabb_stylesdemo_auto_toggle'],
			'DOWNLOAD_DIRECT'	=> isset($download_direct) ? $download_direct : $this->config['vinabb_stylesdemo_download_direct'],
			'PHONE_WIDTH'		=> isset($phone_width) ? $phone_width : $this->config['vinabb_stylesdemo_phone_width'],
			'TABLET_WIDTH'		=> isset($tablet_width) ? $tablet_width : $this->config['vinabb_stylesdemo_tablet_width'],
			'MIN_PHONE_WIDTH'	=> constants::MIN_PHONE_WIDTH,
			'DEFAULT_LANG'		=> $default_lang_name,
			'LANG_ENABLE'		=> isset($lang_enable) ? $lang_enable : $this->config['vinabb_stylesdemo_lang_enable'],
			'ACP_ENABLE'		=> isset($acp_enable) ? $acp_enable : $this->config['vinabb_stylesdemo_acp_enable'],
			'JSON_ENABLE'		=> isset($json_enable) ? $json_enable : $this->config['vinabb_stylesdemo_json_enable'],
			'JSON_URL'			=> isset($json_url) ? $json_url : $this->config['vinabb_stylesdemo_json_url'],
			'SUPPORT_URL'		=> isset($support_url) ? $support_url : $this->config['vinabb_stylesdemo_support_url'],

			'SCREENSHOT_TYPE'			=> isset($screenshot_type) ? $screenshot_type : $this->config['vinabb_stylesdemo_screenshot_type'],
			'SCREENSHOT_TYPE_LOCAL'		=> constants::SCREENSHOT_TYPE_LOCAL,
			'SCREENSHOT_TYPE_JSON'		=> constants::SCREENSHOT_TYPE_JSON,
			'SCREENSHOT_TYPE_PHANTOM'	=> constants::SCREENSHOT_TYPE_PHANTOM,
			'OS_NAME'					=> $this->get_php_os_name(),
			'GET_PHANTOM_FOR_OS'		=> $this->language->lang('GET_PHANTOM_' . ((PHP_INT_SIZE === 4 && $this->get_php_os_name(true) == 'LINUX') ? 'LINUX_32' : $this->get_php_os_name(true)), constants::PHANTOM_URL, $this->ext_root_path),
			'GET_PHANTOM_NO_OS'			=> $this->language->lang('GET_PHANTOM_NO_OS', constants::PHANTOM_URL, $this->ext_root_path),

			'LANG_SWITCH_OPTIONS'	=> $lang_switch_options,
			'RESOLUTION_OPTIONS'	=> $resolution_options,

			'U_ACTION'	=> $this->u_action
		]);
	}

	/**
	* Get ID of the role name ROLE_ADMIN_DEMO
	*
	* @return int
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
			case 'WINNT':
			return ($get_lang_key) ? 'WIN' : 'Windows';

			case 'DARWIN':
			return ($get_lang_key) ? 'MAC' : 'macOS';

			case 'LINUX':
			return ($get_lang_key) ? 'LINUX' : 'Linux';

			case 'FREEBSD':
			return ($get_lang_key) ? 'BSD' : 'FreeBSD';

			default:
			return '';
		}
	}
}
