<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\controller;

use Symfony\Component\HttpFoundation\Response;
use vinabb\stylesdemo\includes\constants;

class main
{
	/** @var \phpbb\db\driver\driver_interface */
    protected $db;

	/** @var \phpbb\config\config */
    protected $config;

	/** @var \phpbb\controller\helper */
    protected $helper;

	/** @var \phpbb\template\template */
    protected $template;

	/** @var \phpbb\user */
    protected $user;

	/** @var \phpbb\auth\auth */
    protected $auth;

	/** @var \phpbb\request\request */
    protected $request;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var \phpbb\file_downloader */
	protected $file_downloader;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $phpbb_admin_path;

	/** @var string */
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
	* @param \phpbb\file_downloader $file_downloader
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
								\phpbb\file_downloader $file_downloader,
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
		$this->file_downloader = $file_downloader;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $this->phpbb_root_path . $phpbb_admin_path;
		$this->php_ext = $php_ext;

		$this->ext_root_path = $this->ext_manager->get_extension_path('vinabb/stylesdemo', true);
		$this->ext_web_path = $this->path_helper->update_web_root_path($this->ext_root_path);
		$this->real_path = dirname(__DIR__) . '/';
	}

	/**
	* Controller for route /demo/{mode}
	*
	* @param string $mode
	* @return Response A Symfony Response object
	*/
	public function handle($mode)
	{
		// Why do you like typing slash at the end? :D
		if ($mode == 'acp/')
		{
			$mode = 'acp';
		}

		$this->user->add_lang_ext('vinabb/stylesdemo', 'demo');

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

		if ($this->config['vinabb_stylesdemo_json_enable'] && !empty($this->config['vinabb_stylesdemo_json_url']))
		{
			// Test file URL
			$test = get_headers($this->config['vinabb_stylesdemo_json_url']);

			if (strpos($test[0], '200') !== false)
			{
				if (function_exists('curl_version'))
				{
					$curl = curl_init($this->config['vinabb_stylesdemo_json_url']);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
					$raw = curl_exec($curl);
					curl_close($curl);
				}
				else
				{
					$url_parts = parse_url($this->config['vinabb_stylesdemo_json_url']);

					try
					{
						$raw = $this->file_downloader->get($url_parts['host'], '', $url_parts['path'], ($url_parts['scheme'] == 'https') ? 443 : 80);
					}
					catch (\phpbb\exception\runtime_exception $e)
					{
						throw new \RuntimeException($this->file_downloader->get_error_string());
					}
				}

				// Parse JSON
				if (!empty($raw))
				{
					$json = json_decode($raw, true);
				}
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
			if ((!$this->auth->acl_get('a_') || $this->user->data['user_id'] == ANONYMOUS) && !$this->config['vinabb_stylesdemo_acp_enable'])
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
				switch ($this->config['vinabb_stylesdemo_screenshot_type'])
				{
					case constants::SCREENSHOT_TYPE_JSON:
						if (!empty($json['acp'][$style_varname]['screenshot']))
						{
							$style_img = $json['acp'][$style_varname]['screenshot'];
						}
						else
						{
							$style_img = "{$this->ext_web_path}assets/screenshots/acp/default.png";
						}
					break;

					case constants::SCREENSHOT_TYPE_PHANTOM:
						//...
					break;

					default:
						if (file_exists("{$this->real_path}assets/screenshots/acp/{$style_varname}.png"))
						{
							$style_img = "{$this->ext_web_path}assets/screenshots/acp/{$style_varname}.png";
						}
						else
						{
							$style_img = "{$this->ext_web_path}assets/screenshots/acp/default.png";
						}
					break;
				}

				// Remote data
				if (isset($json['acp'][$style_varname]))
				{
					$style_name = $json['acp'][$style_varname]['name'];
					$phpbb_version = $json['acp'][$style_varname]['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $json['acp'][$style_varname]['version'];
					$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $json['acp'][$style_varname]['author_name'];
					$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> ' . $json['acp'][$style_varname]['presets'];
					$style_info .= '<br><strong>' . $this->user->lang('RESPONSIVE') . $this->user->lang('COLON') . '</strong> ' . (($json['acp'][$style_varname]['responsive'] == 1) ? $this->user->lang('YES') : $this->user->lang('NO'));
					$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> ' . (($json['acp'][$style_varname]['price']) ? '<code>' . $json['frontend'][$style_varname]['price_label'] . '</code>' : '<code class=green>' . $this->user->lang('FREE') . '</code>');
					$style_details = 'http://vinabb.vn/bb/item/' . $json['acp'][$style_varname]['id'];
					$style_download = $json['acp'][$style_varname]['url'];
					$style_price = $json['acp'][$style_varname]['price'];
					$style_price_label = $json['acp'][$style_varname]['price_label'];
				}
				// Only local data
				else
				{
					$style_name = $cfg['name'];
					$phpbb_version = $cfg['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_version'];

					if (isset($cfg['style_author']) && !empty($cfg['style_author']))
					{
						$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_author'];
					}
					else
					{
						$style_info .= '<br><strong>' . $this->user->lang('COPYRIGHT') . $this->user->lang('COLON') . '</strong> ' . $cfg['copyright'];
					}

					if (isset($cfg['style_presets']) && is_numeric($cfg['style_presets']))
					{
						$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_presets'];
					}

					if (isset($cfg['style_responsive']) && ($cfg['style_responsive'] == 0 || $cfg['style_reponsive'] == 1))
					{
						$style_info .= '<br><strong>' . $this->user->lang('RESPONSIVE') . $this->user->lang('COLON') . '</strong> ' . (($cfg['style_responsive'] == 1) ? $this->user->lang('YES') : $this->user->lang('NO'));
					}

					if (isset($cfg['style_price']) && is_numeric($cfg['style_price']))
					{
						$style_price = $cfg['style_price'];
						$style_price_label = (isset($cfg['style_price_label']) && !empty($cfg['style_price_label'])) ? $cfg['style_price_label'] : $style_price;
						$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> ' . (($style_price) ? "<code>$style_price_label</code>" : '<code class=green>' . $this->user->lang('FREE') . '</code>');
					}
					else
					{
						$style_price = 0;
						$style_price_label = '';
					}

					$style_details = (isset($cfg['style_details']) && !empty($cfg['style_details'])) ? $cfg['style_details'] : '#';
					$style_download = (isset($cfg['style_download']) && !empty($cfg['style_download'])) ? $cfg['style_download'] : '#';
				}

				$this->template->assign_block_vars('styles', array(
					'VARNAME'		=> $style_varname,
					'NAME'			=> $style_name,
					'PHPBB'			=> $this->user->lang('PHPBB_BADGE', $phpbb_version),
					'PHPBB_INFO'	=> '<strong>' . $this->user->lang('PHPBB_VERSION') . $this->user->lang('COLON') . '</strong> <kbd>' . $phpbb_version . '</kbd>',
					'IMG'			=> $style_img,
					'INFO'			=> $style_info,
					'DETAILS'		=> $style_details,
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
				switch ($this->config['vinabb_stylesdemo_screenshot_type'])
				{
					case constants::SCREENSHOT_TYPE_JSON:
						if (!empty($json['frontend'][$style_varname]['screenshot']))
						{
							$style_img = $json['frontend'][$style_varname]['screenshot'];
						}
						else
						{
							$style_img = "{$this->ext_web_path}assets/screenshots/frontend/default.png";
						}
					break;

					case constants::SCREENSHOT_TYPE_PHANTOM:
						//...
					break;

					default:
						if (file_exists("{$this->real_path}assets/screenshots/frontend/{$style_varname}.png"))
						{
							$style_img = "{$this->ext_web_path}assets/screenshots/frontend/{$style_varname}.png";
						}
						else
						{
							$style_img = "{$this->ext_web_path}assets/screenshots/frontend/default.png";
						}
					break;
				}

				// Remote data
				if (isset($json['frontend'][$style_varname]))
				{
					$style_name = $json['frontend'][$style_varname]['name'];
					$phpbb_version = $json['frontend'][$style_varname]['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['version'];
					$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['author_name'];
					$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['presets'];
					$style_info .= '<br><strong>' . $this->user->lang('RESPONSIVE') . $this->user->lang('COLON') . '</strong> ' . (($json['frontend'][$style_varname]['responsive'] == 1) ? $this->user->lang('YES') : $this->user->lang('NO'));
					$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> ' . (($json['frontend'][$style_varname]['price']) ? '<code>' . $json['frontend'][$style_varname]['price_label'] . '</code>' : '<code class=green>' . $this->user->lang('FREE') . '</code>');
					$style_details = 'http://vinabb.vn/bb/item/' . $json['frontend'][$style_varname]['id'];
					$style_download = $json['frontend'][$style_varname]['url'];
					$style_price = $json['frontend'][$style_varname]['price'];
					$style_price_label = $json['frontend'][$style_varname]['price_label'];
				}
				// prosilver
				else if ($style_varname == constants::DEFAULT_STYLE)
				{
					$style_name = constants::DEFAULT_STYLE_NAME;
					$phpbb_version = $cfg['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_version'];
					$style_info .= '<br><strong>' . $this->user->lang('COPYRIGHT') . $this->user->lang('COLON') . '</strong> ' . $cfg['copyright'];
					$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> 0';
					$style_info .= '<br><strong>' . $this->user->lang('RESPONSIVE') . $this->user->lang('COLON') . '</strong> ' . $this->user->lang('YES');
					$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> <code class=green>' . $this->user->lang('FREE') . '</code>';
					$style_details = $style_download = constants::DEFAULT_STYLE_URL;
					$style_price = 0;
					$style_price_label = '';
				}
				// Only local data
				else
				{
					$style_name = $cfg['name'];
					$phpbb_version = $cfg['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_version'];

					if (isset($cfg['style_author']) && !empty($cfg['style_author']))
					{
						$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_author'];
					}
					else
					{
						$style_info .= '<br><strong>' . $this->user->lang('COPYRIGHT') . $this->user->lang('COLON') . '</strong> ' . $cfg['copyright'];
					}

					if (isset($cfg['style_presets']) && is_numeric($cfg['style_presets']))
					{
						$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_presets'];
					}

					if (isset($cfg['style_responsive']) && ($cfg['style_responsive'] == 0 || $cfg['style_reponsive'] == 1))
					{
						$style_info .= '<br><strong>' . $this->user->lang('RESPONSIVE') . $this->user->lang('COLON') . '</strong> ' . (($cfg['style_responsive'] == 1) ? $this->user->lang('YES') : $this->user->lang('NO'));
					}

					if (isset($cfg['style_price']) && is_numeric($cfg['style_price']))
					{
						$style_price = $cfg['style_price'];
						$style_price_label = (isset($cfg['style_price_label']) && !empty($cfg['style_price_label'])) ? $cfg['style_price_label'] : $style_price;
						$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> ' . (($style_price) ? "<code>$style_price_label</code>" : '<code class=green>' . $this->user->lang('FREE') . '</code>');
					}
					else
					{
						$style_price = 0;
						$style_price_label = '';
					}

					$style_details = (isset($cfg['style_details']) && !empty($cfg['style_details'])) ? $cfg['style_details'] : '#';
					$style_download = (isset($cfg['style_download']) && !empty($cfg['style_download'])) ? $cfg['style_download'] : '#';
				}

				$this->template->assign_block_vars('styles', array(
					'VARNAME'		=> $style_varname,
					'NAME'			=> $style_name,
					'PHPBB'			=> $this->user->lang('PHPBB_BADGE', $phpbb_version),
					'PHPBB_INFO'	=> '<strong>' . $this->user->lang('PHPBB_VERSION') . $this->user->lang('COLON') . '</strong> <kbd>' . $phpbb_version . '</kbd>',
					'IMG'			=> $style_img,
					'INFO'			=> $style_info,
					'DETAILS'		=> $style_details,
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

		if ($this->config['vinabb_stylesdemo_lang_enable'] && !empty($this->config['vinabb_stylesdemo_lang_switch']) && $this->config['vinabb_stylesdemo_lang_switch'] != $this->config['default_lang'])
		{
			$sql = 'SELECT *
				FROM ' . LANG_TABLE . '
				WHERE ' . $this->db->sql_in_set('lang_iso', array($this->config['default_lang'], $this->config['vinabb_stylesdemo_lang_switch'])) . '
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
			'LOGO_TEXT'		=> $this->config['vinabb_stylesdemo_logo_text'],

			'DEFAULT_STYLE'		=> constants::DEFAULT_STYLE,
			'CURRENT_LANG'		=> $demo_lang,
			'DEFAULT_LANG'		=> $this->config['default_lang'],
			'DEFAULT_LANG_NAME'	=> $default_lang_name,
			'SWITCH_LANG'		=> $this->config['vinabb_stylesdemo_lang_switch'],
			'SWITCH_LANG_NAME'	=> $switch_lang_name,
			'LANG_TITLE'		=> $lang_title,
			'MODE_TITLE'		=> ($mode == 'acp') ? $this->user->lang('MODE_FRONTEND') : $this->user->lang('MODE_ACP'),

			'EXT_ASSETS_PATH'	=> "{$this->ext_web_path}assets",

			'S_LANG_ENABLE'	=> !empty($lang_title) ? true : false,
			'S_ACP_ENABLE'	=> ($this->config['vinabb_stylesdemo_acp_enable'] && $has_acp_styles) ? true : false,
			'S_JSON_ENABLE'	=> ($this->config['vinabb_stylesdemo_json_enable'] && !empty($this->config['vinabb_stylesdemo_json_url'])) ? true : false,

			'U_MODE'	=> $this->helper->route('vinabb_stylesdemo_route', array('mode' => ($mode == 'acp') ? '' : 'acp')),
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
	*
	* @param $name
	* @param string $underscore
	* @return mixed
	*/
	protected function style_varname_normalize($name, $underscore = '_')
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
