<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\acp;

class data_module
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

		$this->tpl_name = 'data_body';
		$this->page_title = ($mode == 'acp') ? $this->user->lang('ACP_MANAGE_ACP_STYLE_DATA') : $this->user->lang('ACP_MANAGE_STYLE_DATA');
		$this->user->add_lang_ext('vinabb/stylesdemo', array('demo', 'acp_styles_demo'));

		$action = $this->request->variable('action', '');

		add_form_key('vinabb/stylesdemo');

		$s_hidden_fields = '';
		$errors = array();

		switch ($action)
		{
			case 'edit':
				$style_id = $this->request->variable('id', 0);

				if (!$style_id)
				{
					trigger_error($this->user->lang['NO_STYLE'] . adm_back_link($this->u_action), E_USER_WARNING);
				}

				$sql = 'SELECT *
					FROM ' . STYLES_TABLE . "
					WHERE style_id = $style_id";
				$result = $this->db->sql_query($sql);
				$style_data = $this->db->sql_fetchrow($result);
				$this->db->sql_freeresult($result);

				$s_hidden_fields .= '<input type="hidden" name="id" value="' . $style_id . '" />';
				$s_hidden_fields .= '<input type="hidden" name="style_name" value="' . $style_data['style_name'] . '" />';

				$this->template->assign_vars(array(
					'VERSION'		=> isset($style_data['style_version']) ? $style_data['style_version'] : '',
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

					'U_ACTION'	=> $this->u_action,
					'U_BACK'	=> $this->u_action,

					'S_EDIT'			=> true,
					'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
				));

				return;
			break;

			case 'save':
				if (!check_form_key('vinabb/stylesdemo'))
				{
					trigger_error($this->user->lang['FORM_INVALID']. adm_back_link($this->u_action), E_USER_WARNING);
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

				trigger_error('STYLE_DATA_UPDATED' . adm_back_link($this->u_action));
			break;
		}

		$this->template->assign_vars(array(
			'U_ACTION'			=> $this->u_action,
			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
		));

		$sql = 'SELECT *
			FROM ' . STYLES_TABLE . '
			WHERE style_acp = ' . (($mode == 'acp') ? 1 : 0) . '
			ORDER BY style_name';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$this->template->assign_block_vars('styles', array(
				'NAME'			=> $row['style_name'],
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

				'U_EDIT'	=> $this->u_action . '&action=edit&id=' . $row['style_id'])
			);
		}
		$this->db->sql_freeresult($result);
	}
}
