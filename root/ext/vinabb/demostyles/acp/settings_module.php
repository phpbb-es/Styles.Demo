<?php
/**
* This file is part of the VinaBB Demo Styles package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\demostyles\acp;

class settings_module
{
	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\log\log */
	protected $log;

	/* @var \phpbb\request\request */
	protected $request;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var string */
	public $u_action;

	/* @var string */
	public $tpl_name;

	/* @var string */
	public $page_title;

	public function main($id, $mode)
	{
		global $phpbb_container;

		$this->config = $phpbb_container->get('config');
		$this->db = $phpbb_container->get('dbal.conn');
		$this->log = $phpbb_container->get('log');
		$this->request = $phpbb_container->get('request');
		$this->template = $phpbb_container->get('template');
		$this->user = $phpbb_container->get('user');

		$this->tpl_name = 'settings_body';
		$this->page_title = $this->user->lang('ACP_DEMO_STYLES');
		$this->user->add_lang_ext('vinabb/demostyles', 'acp_demo_styles');

		add_form_key('vinabb/demostyles');

		$errors = array();

		// Submit
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('vinabb/demostyles'))
			{
				$errors[] = $this->user->lang['FORM_INVALID'];
			}

			// Get from the form
			$lang_enable = $this->request->variable('lang_enable', false);
			$lang_switch = $this->request->variable('lang_switch', '');
			$acp_enable = $this->request->variable('acp_enable', false);
			$json_enable = $this->request->variable('json_enable', false);
			$json_url = $this->request->variable('json_url', '');

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

			if (empty($errors))
			{
				$this->config->set('vinabb_demostyles_lang_enable', $lang_enable);
				$this->config->set('vinabb_demostyles_lang_switch', $lang_switch);
				$this->config->set('vinabb_demostyles_acp_enable', $acp_enable);
				$this->config->set('vinabb_demostyles_json_enable', $json_enable);
				$this->config->set('vinabb_demostyles_json_url', $json_url);

				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_DEMO_STYLES_SETTINGS');
				trigger_error($this->user->lang('DEMOSTYLES_SETTINGS_UPDATED') . adm_back_link($this->u_action));
			}
			else
			{
				trigger_error(implode('<br>', $errors) . adm_back_link($this->u_action), E_USER_WARNING);				
			}
		}

		// Select an extra language to switch
		$sql = 'SELECT *
			FROM ' . LANG_TABLE . "
			ORDER BY lang_english_name";
		$result = $this->db->sql_query($sql);
		$rows = $this->db->sql_fetchrowset($result);
		$this->db->sql_freeresult($result);

		$selected_lang_switch = isset($lang_switch) ? $lang_switch : $this->config['vinabb_demostyles_lang_switch'];
		$lang_default_name = $lang_switch_options = '';

		if (sizeof($rows) > 1)
		{
			$lang_switch_options .= '<option value=""' . (($selected_lang_switch == '') ? ' selected' : '' ) . '>' . $this->user->lang('SELECT_LANGUAGE') . '</option>';

			foreach ($rows as $row)
			{
				if ($row['lang_iso'] == $this->config['default_lang'])
				{
					$lang_default_name = ($row['lang_english_name'] == $row['lang_local_name']) ? $row['lang_english_name'] : $row['lang_english_name'] . ' (' . $row['lang_local_name'] . ')';
				}
				else
				{
					$lang_switch_options .= '<option value="' . $row['lang_iso'] . '"' . (($selected_lang_switch == $row['lang_iso']) ? ' selected' : '' ) . '>' . $row['lang_english_name'] . ' (' . $row['lang_local_name'] . ')</option>';
				}
			}
		}

		// Output
		$this->template->assign_vars(array(
			'LANG_DEFAULT'	=> $lang_default_name,
			'LANG_ENABLE'	=> isset($lang_enable) ? $lang_enable : $this->config['vinabb_demostyles_lang_enable'],
			'ACP_ENABLE'	=> isset($acp_enable) ? $acp_enable : $this->config['vinabb_demostyles_acp_enable'],
			'JSON_ENABLE'	=> isset($json_enable) ? $json_enable : $this->config['vinabb_demostyles_json_enable'],
			'JSON_URL'		=> (isset($json_url) && !empty($json_url)) ? $json_url : $this->config['vinabb_demostyles_json_url'],

			'LANG_SWITCH_OPTIONS'	=> $lang_switch_options,

			'U_ACTION'	=> $this->u_action,
		));
	}
}
