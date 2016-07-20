<?php
/**
* This file is part of the VinaBB Demo Styles package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\demostyles\controller;

use Symfony\Component\HttpFoundation\Response;
use vinabb\demostyles\includes\constants;

class main
{
	/* @var \phpbb\db\driver\driver_interface */
    protected $db;

	/* @var \phpbb\config\config */
    protected $config;

	/* @var \phpbb\controller\helper */
    protected $helper;

	/* @var \phpbb\template\template */
    protected $template;

	/* @var \phpbb\user */
    protected $user;

	/* @var \phpbb\auth\auth */
    protected $auth;

	/* @var \phpbb\request\request */
    protected $request;

	/* @var \phpbb\extension\manager */
	protected $ext_manager;

	/* @var \phpbb\path_helper */
	protected $path_helper;

	/* @var string */
	protected $phpbb_root_path;

	/* @var string */
	protected $phpbb_admin_path;

	/* @var string */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\db\driver\driver_interface $db
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $helper
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\request\request $request
	* @param \phpbb\extension\manager $ext_manager
	* @param \phpbb\path_helper $path_helper
	* @param string $phpbb_root_path
	* @param string $phpbb_admin_path
	* @param string $php_ext
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
								\phpbb\config\config $config,
								\phpbb\controller\helper $helper,
								\phpbb\template\template $template,
								\phpbb\user $user,
								\phpbb\auth\auth $auth,
								\phpbb\request\request $request,
								\phpbb\extension\manager $ext_manager,
								\phpbb\path_helper $path_helper,
								$phpbb_root_path,
								$phpbb_admin_path,
								$php_ext)
	{
		$this->db = $db;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->auth = $auth;
		$this->request = $request;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $this->phpbb_root_path . $phpbb_admin_path;
		$this->php_ext = $php_ext;

		$this->ext_root_path = $this->ext_manager->get_extension_path('vinabb/demostyles', true);
		$this->ext_web_path = $this->path_helper->update_web_root_path($this->ext_root_path);
	}

	/**
	* Demo controller for route /demo/{name}
	*
	* @param string $name
	* @return Response A Symfony Response object
	*/
	public function handle($mode)
	{
		// Why do you like typing slash at the end? :D
		if ($mode == 'acp/')
		{
			$mode = 'acp';
		}

		$this->user->add_lang_ext('vinabb/demostyles', 'demo');

		// Need to switch to another language?
		if ($this->user->data['user_id'] == ANONYMOUS)
		{
			$demo_lang = $this->request->variable($this->config['cookie_name'] . '_lang', '', true, \phpbb\request\request_interface::COOKIE);
			$demo_lang = empty($demo_lang) ? $this->config['default_lang'] : $demo_lang;
		}
		else
		{
			$demo_lang = $this->user->data['user_lang'];
		}

		// Get more style data from our server
		$json = array();

		if ($this->config['vinabb_demostyles_json_enable'] && !empty($this->config['vinabb_demostyles_json_url']))
		{
			// Test file URL
			$test = get_headers($this->config['vinabb_demostyles_json_url']);

			if (strpos($test[0], '200') !== false)
			{
				// We use cURL here since cURL is faster than file_get_contents()
				$curl = curl_init($this->config['vinabb_demostyles_json_url']);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$raw = curl_exec($curl);
				curl_close($curl);

				// Parse JSON
				$json = json_decode($raw, true);
			}
		}

		// Get the extra ACP style list from <ext>/app/styles/
		$style_dirs = array();
		$scan_dirs = array_diff(scandir("{$this->ext_root_path}app/styles/"), array('..', '.', '.htaccess', '_example'));

		foreach ($scan_dirs as $scan_dir)
		{
			if (is_dir("{$this->ext_root_path}app/styles/{$scan_dir}/") && file_exists("{$this->ext_root_path}app/styles/{$scan_dir}/style.cfg"))
			{
				$style_dirs[] = $scan_dir;
			}
		}

		// Do not switch to ACP mode since there is no admin styles
		$has_acp_styles = sizeof($style_dirs) ? true : false;

		// ACP styles
		if ($mode == 'acp')
		{
			if ((!$this->auth->acl_get('a_') || $this->user->data['user_id'] == ANONYMOUS) && !$this->config['vinabb_demostyles_acp_enable'])
			{
				trigger_error('ACP_STYLES_DISABLED');
			}

			// Nothing to preview
			if (!$has_acp_styles)
			{
				trigger_error('NO_ACP_STYLES');
			}

			// Sort $style_dirs again
			asort($style_dirs);

			foreach ($style_dirs as $style_dir)
			{
				// Get data from style.cfg
				$cfg = parse_cfg_file("{$this->ext_root_path}app/styles/{$style_dir}/style.cfg");

				// Style varname
				$style_varname = $this->style_varname_normalize($style_dir);

				// Style screenshot
				$style_img = "{$this->ext_web_path}assets/screenshots/acp/{$style_varname}.png";

				if (!file_exists($style_img))
				{
					$style_img = "{$this->ext_web_path}assets/screenshots/acp/default.png";
				}

				// Style info
				if (isset($json['acp'][$style_varname]))
				{
					$style_name = $json['acp'][$style_varname]['name'];
					$phpbb_version = $json['acp'][$style_varname]['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $json['acp'][$style_varname]['version'];
					$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $json['acp'][$style_varname]['author_name'];
					$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> ' . $json['acp'][$style_varname]['presets'];
					$style_info .= '<br><strong>' . $this->user->lang('REPONSIVE') . $this->user->lang('COLON') . '</strong> ' . (($json['acp'][$style_varname]['reponsive'] == 1) ? $user->lang('YES') : $user->lang('NO'));
					$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> ' . (($json['acp'][$style_varname]['price']) ? '<code>' . $json['frontend'][$style_varname]['price_label'] . '</code>' : '<code class=green>' . $user->lang('FREE') . '</code>');
					$style_vinabb = 'http://vinabb.vn/bb/item/' . $json['acp'][$style_varname]['id'] . '/download';
					$style_download = $json['acp'][$style_varname]['url'];
					$style_price = $json['acp'][$style_varname]['price'];
					$style_price_label = $json['acp'][$style_varname]['price_label'];
				}
				// Only basic info
				else
				{
					$style_name = $cfg['name'];
					$phpbb_version = $cfg['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_version'];
					$style_info .= '<br><strong>' . $this->user->lang('COPYRIGHT') . $this->user->lang('COLON') . '</strong> ' . $cfg['copyright'];
					$style_vinabb = $style_download = generate_board_url();
					$style_price = 0;
					$style_price_label = '';
				}

				$this->template->assign_block_vars('styles', array(
					'VARNAME'		=> $style_varname,
					'NAME'			=> $style_name,
					'PHPBB'			=> $this->user->lang('PHPBB_BADGE', $phpbb_version),
					'PHPBB_INFO'	=> '<strong>' . $this->user->lang('PHPBB_VERSION') . $this->user->lang('COLON') . '</strong> <kbd>' . $phpbb_version . '</kbd>',
					'IMG'			=> $style_img,
					'INFO'			=> $style_info,
					'VINABB'		=> $style_vinabb,
					'DOWNLOAD'		=> $style_download,
					'PRICE'			=> $style_price,
					'PRICE_LABEL'	=> ($style_price) ? $style_price_label : $this->user->lang('FREE'),
					'URL'			=> append_sid("{$this->ext_root_path}app/index.{$this->php_ext}", 's=' . $style_dir, false, $this->user->session_id),
					'URL_LANG'		=> append_sid("{$this->phpbb_root_path}index.{$this->php_ext}", 'l=1&amp;s=' . $style_dir),
				));
			}
		}
		// Front-end styles
		else
		{
			// Build the style list
			$sql = 'SELECT *
				FROM ' . STYLES_TABLE . '
				WHERE style_active = 1
				ORDER BY style_path';
			$result = $this->db->sql_query($sql);

			while ($row = $this->db->sql_fetchrow($result))
			{
				// Get data from style.cfg
				$cfg = parse_cfg_file($this->phpbb_root_path . 'styles/' . $row['style_path'] . '/style.cfg');

				// Style varname
				$style_varname = $this->style_varname_normalize($row['style_path']);

				// Style screenshot
				$style_img = "{$this->ext_web_path}assets/screenshots/frontend/{$style_varname}.png";

				if (!file_exists($style_img))
				{
					$style_img = "{$this->ext_web_path}assets/screenshots/frontend/default.png";
				}

				// Style info
				if (isset($json['frontend'][$style_varname]))
				{
					$style_name = $json['frontend'][$style_varname]['name'];
					$phpbb_version = $json['frontend'][$style_varname]['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['version'];
					$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['author_name'];
					$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['presets'];
					$style_info .= '<br><strong>' . $this->user->lang('REPONSIVE') . $this->user->lang('COLON') . '</strong> ' . (($json['frontend'][$style_varname]['reponsive'] == 1) ? $this->user->lang('YES') : $this->user->lang('NO'));
					$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> ' . (($json['frontend'][$style_varname]['price']) ? '<code>' . $json['frontend'][$style_varname]['price_label'] . '</code>' : '<code class=green>' . $user->lang('FREE') . '</code>');
					$style_vinabb = 'http://vinabb.vn/bb/item/' . $json['frontend'][$style_varname]['id'] . '/download';
					$style_download = $json['frontend'][$style_varname]['url'];
					$style_price = $json['frontend'][$style_varname]['price'];
					$style_price_label = $json['frontend'][$style_varname]['price_label'];
				}
				// Only basic info
				else
				{
					// prosilver
					if ($style_varname == constants::DEFAULT_STYLE)
					{
						$style_name = constants::DEFAULT_STYLE_NAME;
						$style_vinabb = $style_download = constants::DEFAULT_STYLE_URL;
					}
					else
					{
						$style_name = $row['style_name'];
						$style_vinabb = $style_download = generate_board_url();
					}

					$phpbb_version = $cfg['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_version'];
					$style_info .= '<br><strong>' . $this->user->lang('COPYRIGHT') . $this->user->lang('COLON') . '</strong> ' . $cfg['copyright'];
					$style_price = 0;
					$style_price_label = '';
				}

				$this->template->assign_block_vars('styles', array(
					'VARNAME'		=> $style_varname,
					'NAME'			=> $style_name,
					'PHPBB'			=> $this->user->lang('PHPBB_BADGE', $phpbb_version),
					'PHPBB_INFO'	=> '<strong>' . $this->user->lang('PHPBB_VERSION') . $this->user->lang('COLON') . '</strong> <kbd>' . $phpbb_version . '</kbd>',
					'IMG'			=> $style_img,
					'INFO'			=> $style_info,
					'VINABB'		=> $style_vinabb,
					'DOWNLOAD'		=> $style_download,
					'PRICE'			=> $style_price,
					'PRICE_LABEL'	=> ($style_price) ? $style_price_label : $this->user->lang('FREE'),
					'URL'			=> append_sid("{$this->phpbb_root_path}index.{$this->php_ext}", 'style=' . $row['style_id']),
					'URL_LANG'		=> append_sid("{$this->phpbb_root_path}index.{$this->php_ext}", 'l=1&amp;style=' . $row['style_id']),
				));
			}
			$this->db->sql_freeresult($result);
		}

		// Get lang info
		$lang_title = $default_lang_name = $switch_lang_name = '';

		if ($this->config['vinabb_demostyles_lang_enable'] && !empty($this->config['vinabb_demostyles_lang_switch']) && $this->config['vinabb_demostyles_lang_switch'] != $this->config['default_lang'])
		{
			$sql = 'SELECT *
				FROM ' . LANG_TABLE . '
				WHERE ' . $this->db->sql_in_set('lang_iso', array($this->config['default_lang'], $this->config['vinabb_demostyles_lang_switch'])) . '
				ORDER BY lang_english_name';
			$result = $this->db->sql_query($sql);
			$rows = $this->db->sql_fetchrowset($result);
			$this->db->sql_freeresult($result);

			foreach ($rows as $row)
			{
				if ($row['lang_iso'] == $this->config['default_lang'])
				{
					$default_lang_name = $row['lang_local_name'];
				}
				else
				{
					$switch_lang_name = $row['lang_local_name'];
				}
			}

			$lang_title = ($demo_lang == $this->config['default_lang']) ? $this->user->lang('LANG_SWITCH', $default_lang_name, $switch_lang_name) : $this->user->lang('LANG_SWITCH', $switch_lang_name, $default_lang_name);
		}

		// Assign index specific vars
		$this->template->assign_vars(array(
			'PREFIX_URL'	=> generate_board_url() . '/',

			'DEFAULT_STYLE'		=> constants::DEFAULT_STYLE,
			'CURRENT_LANG'		=> $demo_lang,
			'DEFAULT_LANG'		=> $this->config['default_lang'],
			'DEFAULT_LANG_NAME'	=> $default_lang_name,
			'SWITCH_LANG'		=> $this->config['vinabb_demostyles_lang_switch'],
			'SWITCH_LANG_NAME'	=> $switch_lang_name,
			'LANG_TITLE'			=> $lang_title,
			'MODE_TITLE'		=> ($mode == 'acp') ? $this->user->lang('MODE_FRONTEND') : $this->user->lang('MODE_ACP'),

			'EXT_ASSETS_PATH'	=> "{$this->ext_web_path}assets",

			'S_LANG_ENABLE'	=> !empty($lang_title) ? true : false,
			'S_ACP_ENABLE'	=> ($this->config['vinabb_demostyles_acp_enable'] && $has_acp_styles) ? true : false,
			'S_JSON_ENABLE'	=> ($this->config['vinabb_demostyles_json_enable'] && !empty($this->config['vinabb_demostyles_json_url'])) ? true : false,

			'U_MODE'	=> $this->helper->route('vinabb_demostyles_route', array('mode' => ($mode == 'acp') ? '' : 'acp')),
		));

		return $this->helper->render('demo_body.html', $mode);
	}

	/*
	* Examples:
	*	My Style			->	my_style
	*	My & Our Style		->	my_and_our_style
	*	My&Our Style		->	my_and_our_style
	*	My - First Style	->	my_first_style
	*	My.First Style		->	my_first_style
	*	Aloh@ Style			->	aloha_style
	*/
	public function style_varname_normalize($name, $underscore = '_')
	{
		$name = str_replace('&', ' and ', $name);
		$name = str_replace('-', ' ', $name);
		$name = str_replace('.', ' ', $name);
		$name = str_replace('@', 'a', $name);
		$name = strtolower(trim($name));
		$name = str_replace(' ', $underscore, $name);

		return $name;
	}
}