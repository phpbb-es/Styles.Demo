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
		global $phpbb_container, $phpbb_root_path, $phpbb_admin_path, $phpEx;

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
		$this->language->add_lang(array('demo', 'acp_styles_demo'), 'vinabb/stylesdemo');

		$action = $this->request->variable('action', '');

		// Pagination
		$start = $this->request->variable('start', 0);
		$per_page = constants::STYLES_PER_PAGE;

		add_form_key('vinabb/stylesdemo');

		$s_hidden_fields = '';
		$errors = array();

		switch ($action)
		{
			// Only ACP styles
			case 'add':
				if ($mode == 'acp')
				{
					$add_styles = $this->request->variable('add_styles', array(''));

					// Submit
					if ($this->request->is_set_post('submit') && sizeof($add_styles))
					{
						$sql_ary = array();
						$style_names = array();
						$duplicate_styles = array();

						// Get existing ACP styles
						$existing_styles = array();

						$sql = "SELECT style_name, style_path
							FROM " . $this->style_table;
						$result = $this->db->sql_query($sql);

						while ($row = $this->db->sql_fetchrow($result))
						{
							$existing_styles[$row['style_name']] = $row['style_path'];
						}
						$this->db->sql_freeresult($result);

						foreach ($add_styles as $add_style)
						{
							// Get data from style.cfg
							$cfg = parse_cfg_file("{$this->ext_root_path}app/styles/{$add_style}/style.cfg");

							// Style name
							$style_name = isset($cfg['name']) ? $cfg['name'] : $add_style;

							// Check duplicate names
							if (!isset($style_names[$style_name]) && !isset($existing_styles[$style_name]))
							{
								$style_names[$style_name] = $add_style;

								// If not duplicate, add to SQL array
								$sql_ary[] = array(
									'style_name'			=> $style_name,
									'style_copyright'		=> isset($cfg['copyright']) ? $cfg['copyright'] : '',
									'style_path'			=> $add_style,
									'style_version'			=> isset($cfg['style_version']) ? $cfg['style_version'] : '',
									'style_phpbb_version'	=> isset($cfg['phpbb_version']) ? $cfg['phpbb_version'] : '',
								);
							}
							else
							{
								$duplicate_styles[$add_style] = $style_name;
							}
						}

						if (sizeof($style_names))
						{
							$this->db->sql_multi_insert($this->style_table, $sql_ary);
							$this->config->increment('vinabb_stylesdemo_num_acp_styles', sizeof($style_names), true);
							$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_STYLE_ADD', false, array(implode(', ', array_keys($style_names))));
						}

						$duplicate_message = '';

						if (sizeof($duplicate_styles))
						{
							foreach ($duplicate_styles as $path	=> $name)
							{
								$duplicate_message .= (empty($duplicate_message) ? '' : '<br>') . $this->language->lang('ERROR_DUPLICATE_STYLE_PATH', $this->ext_root_path . 'app/styles/' . $path) . ' | ' . $this->language->lang('ERROR_DUPLICATE_STYLE_NAME', $name);
							}
						}

						$success_lang = $this->language->lang('STYLE_DATA_ADDED');
						$error_lang = $this->language->lang('ERROR_DUPLICATE_STYLE', $duplicate_message);

						if (sizeof($style_names) && sizeof($duplicate_styles))
						{
							trigger_error($success_lang . '<br><br>' . $error_lang . adm_back_link($this->u_action));
						}
						else if (sizeof($style_names))
						{
							trigger_error($success_lang . adm_back_link($this->u_action));
						}
						else
						{
							trigger_error($error_lang . adm_back_link($this->u_action), E_USER_WARNING);
						}
					}
				}
			break;

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
					'DETAILS'		=> isset($style_data['style_details']) ? $style_data['style_details'] : '',
					'SUPPORT'		=> isset($style_data['style_support']) ? $style_data['style_support'] : '',

					'U_ACTION'	=> $this->u_action . "&action=$action",
					'U_BACK'	=> $this->u_action . "&start=$start",

					'S_EDIT'			=> true,
					'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
				));

				// Parse mirror data
				if (!empty($style_data['style_mirror']))
				{
					$style_data['style_mirror'] = unserialize($style_data['style_mirror']);

					foreach ($style_data['style_mirror'] as $mirror_url => $mirror_name)
					{
						$this->template->assign_block_vars('mirrors', array(
							'URL'	=> $mirror_url,
							'NAME'	=> !empty($mirror_name) ? $mirror_name : '',
						));
					}
				}
				// Add an empty line for input
				else
				{
					$this->template->assign_block_vars('mirrors', array(
						'URL'	=> '',
						'NAME'	=> '',
					));
				}

				// Submit
				if ($this->request->is_set_post('submit'))
				{
					if (!check_form_key('vinabb/stylesdemo'))
					{
						trigger_error($this->language->lang('FORM_INVALID'). adm_back_link($this->u_action), E_USER_WARNING);
					}

					$style_id = $this->request->variable('id', 0);
					$style_name = $this->request->variable('style_name', '', true);
					$style_version = strtoupper($this->request->variable('style_version', ''));
					$style_phpbb_version = strtoupper($this->request->variable('style_phpbb_version', ''));
					$style_author = $this->request->variable('style_author', '', true);
					$style_author_url = $this->request->variable('style_author_url', '');
					$style_presets = $this->request->variable('style_presets', 0);
					$style_responsive = $this->request->variable('style_responsive', false);
					$style_price = $this->request->variable('style_price', 0);
					$style_price_label = $this->request->variable('style_price_label', '');
					$style_download = $this->request->variable('style_download', '');
					$style_mirror_names = $this->request->variable('style_mirror_names', array(''));
					$style_mirror_urls = $this->request->variable('style_mirror_urls', array(''));
					$style_details = $this->request->variable('style_details', '');
					$style_support = $this->request->variable('style_support', '');

					// Check style name
					if (empty($style_name))
					{
						trigger_error($this->language->lang('ERROR_STYLE_NAME_EMPTY'). adm_back_link($this->u_action), E_USER_WARNING);
					}
					else
					{
						$sql = 'SELECT style_name
							FROM ' . $this->style_table . "
							WHERE style_id <> $style_id
								AND style_name = '" . $this->db->sql_escape($style_name) . "'";
						$result = $this->db->sql_query($sql);
						$rows = $this->db->sql_fetchrowset($result);
						$this->db->sql_freeresult($result);

						if (sizeof($rows))
						{
							trigger_error($this->language->lang('ERROR_STYLE_NAME_EXIST', $style_name). adm_back_link($this->u_action), E_USER_WARNING);
						}
					}

					// Check items based on the style price
					if ($style_price)
					{
						// Try to format price label with the entered price value
						if (empty($style_price_label))
						{
							$style_price_label = sprintf(constants::CURRENCY_SYMBOL, $style_price);
						}

						// Mirrors are not available for commercial styles
						unset($style_mirror_urls);
						$style_mirror_urls = array();
					}
					else
					{
						// Empty price label if free
						$style_price_label = '';
					}

					// Get mirror links
					$i = 0;
					$style_mirrors = array();

					foreach ($style_mirror_urls as $style_mirror_url)
					{
						$style_mirror_url = htmlspecialchars($style_mirror_url);

						if (!empty($style_mirror_url) && !isset($style_mirrors[$style_mirror_url]))
						{
							$style_mirrors[$style_mirror_url] = (isset($style_mirror_names[$i]) && !empty($style_mirror_names[$i])) ? $style_mirror_names[$i] : '';
						}

						$i++;
					}

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
						'style_mirror'			=> (sizeof($style_mirrors)) ? serialize($style_mirrors) : '',
						'style_details'			=> $style_details,
						'style_support'			=> $style_support,
					);

					if ($style_id)
					{
						$this->db->sql_query('UPDATE ' . $this->style_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE style_id = ' . $style_id);
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

				if ($mode == 'frontend' && $style_id == $this->config['default_style'])
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
					trigger_error($this->language->lang['NO_STYLE'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql = 'UPDATE ' . $this->style_table . '
					SET style_active = ' . (($action == 'enable') ? 1 : 0) . "
					WHERE style_id = $style_id";
				$this->db->sql_query($sql);
			break;

			// Only ACP styles
			case 'delete':
				if ($mode == 'acp')
				{
					$style_id = $this->request->variable('id', 0);

					if (!$style_id)
					{
						trigger_error($this->language->lang['NO_STYLE_ID'] . adm_back_link($this->u_action), E_USER_WARNING);
					}

					if (confirm_box(true))
					{
						$sql = 'SELECT style_name
							FROM ' . $this->style_table . "
							WHERE style_id = $style_id";
						$result = $this->db->sql_query($sql);
						$style_name = $this->db->sql_fetchfield('style_name');
						$this->db->sql_freeresult($result);

						$sql = 'DELETE
							FROM ' . $this->style_table . "
							WHERE style_id = $style_id";
						$this->db->sql_query($sql);

						$this->config->increment('vinabb_stylesdemo_num_acp_styles', -1, true);

						$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_STYLE_ADD', false, array($style_name));

						trigger_error($this->language->lang('STYLE_DATA_DELETED') . adm_back_link($this->u_action));
					}
					else
					{
						confirm_box(false, $this->language->lang['CONFIRM_DELETE_STYLE_DATA'], build_hidden_fields(array(
							'i'			=> $id,
							'mode'		=> $mode,
							'id'		=> $style_id,
							'action'	=> 'delete',
						)));
					}
				}
			breaK;

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
						$cfg = ($mode == 'acp') ? parse_cfg_file("{$this->ext_root_path}app/styles/{$row['style_path']}/style.cfg") : parse_cfg_file("{$phpbb_root_path}styles/{$row['style_path']}/style.cfg");

						if (isset($cfg[$cfg_field]) && version_compare($cfg[$cfg_field], $row[$sql_field], '>'))
						{
							$sql = 'UPDATE ' . $this->style_table . "
								SET $sql_field = '" . strtoupper($cfg[$cfg_field]) . "'
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
			if ($mode == 'acp')
			{
				$u_delete = $this->u_action . '&action=delete&id=' . $row['style_id'];
			}
			else
			{
				$u_delete = ($row['style_id'] == $this->config['default_style']) ? '' : append_sid("{$phpbb_admin_path}index.$phpEx", 'i=acp_styles&action=uninstall&hash=' . generate_link_hash('uninstall') . '&id=' . $row['style_id']);
			}

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

				'U_ENABLE'		=> $this->u_action . "&action=enable&start=$start&id=" . $row['style_id'],
				'U_DISABLE'		=> ($mode == 'frontend' && $row['style_id'] == $this->config['default_style']) ? '' : $this->u_action . "&action=disable&start=$start&id=" . $row['style_id'],
				'U_EDIT'		=> $this->u_action . '&action=edit&id=' . $row['style_id'],
				'U_DELETE'		=> $u_delete,
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

		// Add new ACP styles
		if ($mode == 'acp')
		{
			// Get existing ACP styles
			$existing_styles = array();

			$sql = "SELECT style_name, style_path
				FROM " . $this->style_table;
			$result = $this->db->sql_query($sql);

			while ($row = $this->db->sql_fetchrow($result))
			{
				$existing_styles[$row['style_path']] = $row['style_name'];
			}
			$this->db->sql_freeresult($result);

			// Available ACP styles to add from <ext>/app/styles/
			$style_dirs = array();
			$scan_dirs = array_diff(scandir("{$this->ext_root_path}app/styles/"), array('..', '.', '.htaccess', '_example'));

			foreach ($scan_dirs as $scan_dir)
			{
				if (is_dir("{$this->ext_root_path}app/styles/{$scan_dir}/") && !isset($existing_styles[$scan_dir]) && file_exists("{$this->ext_root_path}app/styles/{$scan_dir}/style.cfg"))
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

	/**
	* List styles with pagination
	*
	* @param     $style_table
	* @param     $styles
	* @param     $style_count
	* @param int $limit
	* @param int $offset
	*
	* @return int
	*/
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
