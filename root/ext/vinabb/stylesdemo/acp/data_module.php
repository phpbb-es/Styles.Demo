<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\acp;

use vinabb\stylesdemo\includes\constants;

class data_module
{
	/** @var string */
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container, $phpbb_root_path, $phpEx;

		$this->auth = $phpbb_container->get('auth');
		$this->config = $phpbb_container->get('config');
		$this->db = $phpbb_container->get('dbal.conn');
		$this->log = $phpbb_container->get('log');
		$this->request = $phpbb_container->get('request');
		$this->template = $phpbb_container->get('template');
		$this->user = $phpbb_container->get('user');
		$this->language = $phpbb_container->get('language');
		$this->pagination= $phpbb_container->get('pagination');
		$this->ext_manager = $phpbb_container->get('ext.manager');
		$this->ext_root_path = $this->ext_manager->get_extension_path('vinabb/stylesdemo', true);
		$this->style_table = ($mode == 'acp') ? $phpbb_container->getParameter('core.table_prefix') . 'acp_styles' : STYLES_TABLE;

		$this->tpl_name = 'data_body';
		$this->page_title = ($mode == 'acp') ? $this->language->lang('ACP_MANAGE_ACP_STYLE_DATA') : $this->language->lang('ACP_MANAGE_STYLE_DATA');
		$this->user->add_lang_ext('vinabb/stylesdemo', array('demo', 'acp_styles_demo'));

		$action = $this->request->variable('action', '');

		// Pagination
		$start = $this->request->variable('start', 0);
		$per_page = constants::STYLES_PER_PAGE;

		add_form_key('vinabb/stylesdemo');

		$s_hidden_fields = '';
		$errors = array();

		switch ($action)
		{
			case 'edit':
				$style_id = $this->request->variable('id', 0);

				if (!$style_id)
				{
					trigger_error($this->language->lang('NO_STYLE_ID') . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql = 'SELECT *
					FROM ' . $this->style_table . "
					WHERE style_id = $style_id";
				$result = $this->db->sql_query($sql);
				$style_data = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				$s_hidden_fields .= '<input type="hidden" name="id" value="' . $style_id . '" />';

				$this->template->assign_vars(array(
					'NAME'			=> isset($style_data['style_name']) ? $style_data['style_name'] : '',
					'STYLE_VERSION'	=> isset($style_data['style_version']) ? $style_data['style_version'] : '',
					'PHPBB_VERSION'	=> isset($style_data['style_phpbb_version']) ? $style_data['style_phpbb_version'] : '',
					'AUTHOR'		=> isset($style_data['style_author']) ? $style_data['style_author'] : '',
					'AUTHOR_URL'	=> isset($style_data['style_author_url']) ? $style_data['style_author_url'] : '',
					'PRESETS'		=> isset($style_data['style_presets']) ? $style_data['style_presets'] : 0,
					'RESPONSIVE'	=> (isset($style_data['style_responsive']) && $style_data['style_responsive']) ? true : false,
					'PRICE'			=> isset($style_data['style_price']) ? $style_data['style_price'] : 0,
					'PRICE_LABEL'	=> isset($style_data['style_price_label']) ? $style_data['style_price_label'] : '',
					'DOWNLOAD'		=> isset($style_data['style_download']) ? $style_data['style_download'] : '',
					'MIRROR'		=> isset($style_data['style_mirror']) ? $style_data['style_mirror'] : '',
					'DETAILS'		=> isset($style_data['style_details']) ? $style_data['style_details'] : '',
					'SUPPORT'		=> isset($style_data['style_support']) ? $style_data['style_support'] : '',

					'U_ACTION'	=> $this->u_action . "&action=$action",
					'U_BACK'	=> $this->u_action . "&start=$start",

					'S_EDIT'			=> true,
					'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
				));

				// Submit
				if ($this->request->is_set_post('submit'))
				{
					if (!check_form_key('vinabb/stylesdemo'))
					{
						trigger_error($this->language->lang('FORM_INVALID'). adm_back_link($this->u_action), E_USER_WARNING);
					}

					$style_id = $this->request->variable('id', 0);
					$style_name = $this->request->variable('style_name', '');
					$style_version = $this->request->variable('style_version', '');
					$style_phpbb_version = $this->request->variable('style_phpbb_version', '');
					$style_author = $this->request->variable('style_author', '');
					$style_author_url = $this->request->variable('style_author_url', '');
					$style_presets = $this->request->variable('style_presets', 0);
					$style_responsive = $this->request->variable('style_responsive', false);
					$style_price = $this->request->variable('style_price', 0);
					$style_price_label = $this->request->variable('style_price_label', '');
					$style_download = $this->request->variable('style_download', '');
					$style_mirror = $this->request->variable('style_mirror', '');
					$style_details = $this->request->variable('style_details', '');
					$style_support = $this->request->variable('style_support', '');

					$sql_ary = array(
						'style_name'			=> $style_name,
						'style_version'			=> $style_version,
						'style_phpbb_version'	=> $style_phpbb_version,
						'style_author'			=> $style_author,
						'style_author_url'		=> $style_author_url,
						'style_presets'			=> $style_presets,
						'style_responsive'		=> $style_responsive,
						'style_price'			=> $style_price,
						'style_price_label'		=> $style_price_label,
						'style_download'		=> $style_download,
						'style_mirror'			=> $style_mirror,
						'style_details'			=> $style_details,
						'style_support'			=> $style_support,
					);

					if ($style_id)
					{
						$this->db->sql_query('UPDATE ' . STYLES_TABLE . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE style_id = ' . $style_id);
					}

					$log_action = ($mode == 'acp') ? 'LOG_ACP_STYLE_DATA_EDIT' : 'LOG_STYLE_DATA_EDIT';
					$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $log_action, false, array($style_name));

					trigger_error($this->language->lang('STYLE_DATA_UPDATED') . adm_back_link($this->u_action));
				}
			break;

			case 'enable':
			case 'disable':
				$style_id = $this->request->variable('id', 0);

				if (!$style_id)
				{
					trigger_error($this->language->lang['NO_STYLE_ID'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				if ($style_id == $this->config['default_style'])
				{
					trigger_error($this->language->lang['ERROR_DISABLE_DEFAULT_STYLE'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql = 'SELECT *
					FROM ' . $this->style_table . "
					WHERE style_id = $style_id";
				$result = $this->db->sql_query($sql);
				$row = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				if (!$row)
				{
					trigger_error($this->user->lang['NO_STYLE'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql = 'UPDATE ' . $this->style_table . '
					SET style_active = ' . (($action == 'enable') ? 1 : 0) . "
					WHERE style_id = $style_id";
				$this->db->sql_query($sql);
			break;

			case 'update_version':
			case 'update_phpbb_version':
				$update_count = 0;
				$confirm_lang = ($action == 'update_version') ? 'CFG_UPDATE_VERSION_CONFIRM' : 'CFG_UPDATE_PHPBB_VERSION_CONFIRM';
				$success_lang = ($action == 'update_version') ? 'CFG_UPDATE_VERSION_SUCCESS' : 'CFG_UPDATE_PHPBB_VERSION_SUCCESS';

				if (!confirm_box(true))
				{
					confirm_box(false, $this->language->lang($confirm_lang), build_hidden_fields(array(
						'i'			=> $id,
						'mode'		=> $mode,
						'action'	=> $action,
					)));
				}
				else
				{
					if (!$this->auth->acl_get('a_board'))
					{
						trigger_error($this->language->lang['NO_AUTH_OPERATION'] . adm_back_link($this->u_action), E_USER_WARNING);
					}

					$cfg_field = ($action == 'update_version') ? 'style_version' : 'phpbb_version';
					$sql_field = ($action == 'update_version') ? 'style_version' : 'style_phpbb_version';

					$sql = "SELECT style_id, style_path, $sql_field
						FROM " . $this->style_table;
					$result = $this->db->sql_query($sql);

					while ($row = $this->db->sql_fetchrow($result))
					{
						// Get data from style.cfg
						$cfg = parse_cfg_file("{$phpbb_root_path}styles/{$row['style_path']}/style.cfg");

						if (isset($cfg[$cfg_field]) && version_compare($cfg[$cfg_field], $row[$sql_field], '>'))
						{
							$sql = 'UPDATE ' . $this->style_table . "
								SET $sql_field = '" . $cfg[$cfg_field] . "'
								WHERE style_id = " . $row['style_id'];
							$this->db->sql_query($sql);

							$update_count++;
						}
					}
					$this->db->sql_freeresult($result);

					if ($this->request->is_ajax())
					{
						trigger_error($this->language->lang($success_lang, $update_count));
					}
				}

				$this->template->assign_vars(array(
					'U_ACTION'	=> $this->u_action . "&action=$action",
				));
			break;
		}

		// Manage styles
		$styles = array();
		$style_count = 0;

		$start = $this->list_styles($this->style_table, $styles, $style_count, $per_page, $start);

		foreach ($styles as $row)
		{
			$this->template->assign_block_vars('styles', array(
				'NAME'			=> $row['style_name'],
				'ACTIVE'		=> ($row['style_active']) ? true : false,
				'PATH'			=> $row['style_path'],
				'VERSION'		=> $row['style_version'],
				'PHPBB_VERSION'	=> $row['style_phpbb_version'],
				'AUTHOR'		=> $row['style_author'],
				'AUTHOR_URL'	=> $row['style_author_url'],
				'PRESETS'		=> $row['style_presets'],
				'RESPONSIVE'	=> ($row['style_responsive']) ? true : false,
				'PRICE'			=> $row['style_price'],
				'PRICE_LABEL'	=> $row['style_price_label'],
				'DOWNLOAD'		=> $row['style_download'],
				'MIRROR'		=> $row['style_mirror'],
				'DETAILS'		=> $row['style_details'],
				'SUPPORT'		=> $row['style_support'],

				'U_ENABLE'	=> $this->u_action . '&action=enable&id=' . $row['style_id'],
				'U_DISABLE'	=> ($row['style_id'] == $this->config['default_style']) ? '' : $this->u_action . '&action=disable&id=' . $row['style_id'],
				'U_EDIT'	=> $this->u_action . '&action=edit&id=' . $row['style_id'],
			));
		}

		$this->pagination->generate_template_pagination($this->u_action, 'pagination', 'start', $style_count, $per_page, $start);

		$this->template->assign_vars(array(
			'TOTAL_STYLES'		=> $style_count,
			'TOTAL_STYLES_LANG'	=> ($mode == 'acp') ? 'ACP_STYLES' : 'STYLES',

			'U_ACTION'			=> $this->u_action . "&action=$action&start=$start",

			'S_ACP_STYLES'		=> ($mode == 'acp') ? true : false,
			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
		));

		// Available ACP styles to add from <ext>/app/styles/
		if ($mode == 'acp')
		{
			$style_dirs = array();
			$scan_dirs = array_diff(scandir("{$this->ext_root_path}app/styles/"), array('..', '.', '.htaccess', '_example'));

			foreach ($scan_dirs as $scan_dir)
			{
				if (is_dir("{$this->ext_root_path}app/styles/{$scan_dir}/") && file_exists("{$this->ext_root_path}app/styles/{$scan_dir}/style.cfg"))
				{
					$style_dirs[] = $scan_dir;
				}
			}

			foreach ($style_dirs as $style_dir)
			{
				// Get data from style.cfg
				$cfg = parse_cfg_file("{$this->ext_root_path}app/styles/{$style_dir}/style.cfg");

				$this->template->assign_block_vars('install_styles', array(
					'NAME'			=> isset($cfg['name']) ? $cfg['name'] : $style_dir,
					'PATH'			=> $style_dir,
					'VERSION'		=> isset($cfg['style_version']) ? $cfg['style_version'] : '',
					'PHPBB_VERSION'	=> isset($cfg['phpbb_version']) ? $cfg['phpbb_version'] : '',
				));
			}

			$this->template->assign_vars(array(
				'HAS_AVAILABLE_STYLES'	=> sizeof($style_dirs) ? true : false,
				'ADD_STYLE_EXPLAIN'		=> sizeof($style_dirs) ? $this->language->lang('ACP_ADD_ACP_STYLE_EXPLAIN') : $this->language->lang('ACP_ADD_ACP_STYLE_UNAVAILABLE', $this->ext_root_path . 'app/styles/'),
			));
		}
	}

	private function list_styles(&$style_table, &$styles, &$style_count, $limit = 0, $offset = 0)
	{
		$sql = "SELECT COUNT(style_id) AS style_count
			FROM $style_table";
		$result = $this->db->sql_query($sql);
		$style_count = (int) $this->db->sql_fetchfield('style_count');
		$this->db->sql_freeresult($result);

		if ($style_count == 0)
		{
			return 0;
		}

		if ($offset >= $style_count)
		{
			$offset = ($offset - $limit < 0) ? 0 : $offset - $limit;
		}

		$sql = "SELECT *
			FROM $style_table
			ORDER BY style_name";
		$result = $this->db->sql_query_limit($sql, $limit, $offset);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$styles[] = $row;
		}
		$this->db->sql_freeresult($result);

		return $offset;
	}
}
