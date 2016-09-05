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

	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\config\config */
    protected $config;

	/** @var \phpbb\controller\helper */
    protected $helper;

	/** @var \phpbb\template\template */
    protected $template;

	/** @var \phpbb\user */
    protected $user;

	/** @var \phpbb\language\language */
	protected $language;

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
	protected $acp_styles_table;

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
	* @param \phpbb\cache\driver\driver_interface $cache
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $helper
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \phpbb\language\language $language
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\request\request $request
	* @param \phpbb\extension\manager $ext_manager
	* @param \phpbb\path_helper $path_helper
	* @param \phpbb\file_downloader $file_downloader
	* @param string $acp_styles_table
	* @param string $phpbb_root_path
	* @param string $phpbb_admin_path
	* @param string $php_ext
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
								\phpbb\cache\driver\driver_interface $cache,
								\phpbb\config\config $config,
								\phpbb\controller\helper $helper,
								\phpbb\template\template $template,
								\phpbb\user $user,
								\phpbb\language\language $language,
								\phpbb\auth\auth $auth,
								\phpbb\request\request $request,
								\phpbb\extension\manager $ext_manager,
								\phpbb\path_helper $path_helper,
								\phpbb\file_downloader $file_downloader,
								$acp_styles_table,
								$phpbb_root_path,
								$phpbb_admin_path,
								$php_ext)
	{
		$this->db = $db;
		$this->cache = $cache;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->language = $language;
		$this->auth = $auth;
		$this->request = $request;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->file_downloader = $file_downloader;
		$this->acp_styles_table = $acp_styles_table;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $this->phpbb_root_path . $phpbb_admin_path;
		$this->php_ext = $php_ext;

		$this->ext_root_path = $this->ext_manager->get_extension_path('vinabb/stylesdemo', true);
		$this->ext_web_path = $this->path_helper->update_web_root_path($this->ext_root_path);
		$this->set_time_limit = false;
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

		$this->language->add_lang('demo', 'vinabb/stylesdemo');

		// Get more style data from mom server
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

		// Checking for ACP styles
		if ($mode == 'acp')
		{
			if ((!$this->auth->acl_get('a_') || $this->user->data['user_id'] == ANONYMOUS) && !$this->config['vinabb_stylesdemo_acp_enable'])
			{
				trigger_error('ACP_STYLES_DISABLED');
			}

			// Nothing to preview
			if (!$this->config['vinabb_stylesdemo_num_acp_styles'])
			{
				trigger_error('NO_ACP_STYLES');
			}

			// If the registration is enabled and registered users access the fake ACP, just logout them as guest
			if ($this->user->data['is_registered'] && !$this->auth->acl_get('a_'))
			{
				$this->user->session_kill();
			}
		}

		// Build the style data
		$style_data = array();

		$sql = 'SELECT *
			FROM ' . (($mode == 'acp') ? $this->acp_styles_table : STYLES_TABLE) . '
			WHERE style_active = 1
			ORDER BY ' . (($this->config['vinabb_stylesdemo_json_enable']) ? 'style_path' : 'style_name');
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Style varname
			$style_varname = $this->style_varname_normalize($row['style_path']);

			// JSON tree
			$json_tree = ($mode == 'acp') ? 'acp' : 'frontend';
			
			// Style screenshot
			switch ($this->config['vinabb_stylesdemo_screenshot_type'])
			{
				case constants::SCREENSHOT_TYPE_JSON:
					if (!empty($json[$json_tree][$style_varname]['screenshot']))
					{
						$style_img = $json[$json_tree][$style_varname]['screenshot'];
					}
					else
					{
						$style_img = "{$this->ext_web_path}assets/screenshots/frontend/default.png";
					}
				break;

				case constants::SCREENSHOT_TYPE_PHANTOM:
					$screenshot_filename = (($mode == 'acp') ? 'acp_' : '') . $style_varname . '_' . $this->config['vinabb_stylesdemo_screenshot_width'] . 'x' . $this->config['vinabb_stylesdemo_screenshot_height'];

					if (file_exists("{$this->ext_root_path}bin/images/{$screenshot_filename}" . constants::SCREENSHOT_EXT))
					{
						$style_img = "{$this->ext_web_path}bin/images/{$screenshot_filename}" . constants::SCREENSHOT_EXT;
					}
					else
					{
						if (!$this->set_time_limit)
						{
							set_time_limit(0);

							$this->set_time_limit = true;
						}

						$preview_url = generate_board_url() . "/index.{$this->php_ext}?style={$row['style_id']}";
						$script = file_get_contents("{$this->ext_root_path}assets/js/phantom.js");
						$script = str_replace(array('{phantom.url}', '{phantom.img}', '{phantom.width}', '{phantom.height}'), array($preview_url, "{$this->ext_root_path}bin/images/{$screenshot_filename}" . constants::SCREENSHOT_EXT, $this->config['vinabb_stylesdemo_screenshot_width'], $this->config['vinabb_stylesdemo_screenshot_height']), $script);

						// Create .js data file for PhantomJS
						file_put_contents("{$this->ext_root_path}bin/js/{$screenshot_filename}.js", $script);

						// Phantom! Summon... Summon...
						try
						{
							exec("{$this->ext_root_path}bin/phantomjs {$this->ext_root_path}bin/js/{$screenshot_filename}.js");

							$style_img = "{$this->ext_web_path}bin/images/{$screenshot_filename}" . constants::SCREENSHOT_EXT;
						}
						catch (\phpbb\exception\runtime_exception $e)
						{
							$style_img = "{$this->ext_web_path}assets/screenshots/frontend/default.png";
						}
					}
					break;

				default:
					if (file_exists("{$this->ext_root_path}assets/screenshots/frontend/{$style_varname}" . constants::SCREENSHOT_EXT))
					{
						$style_img = "{$this->ext_web_path}assets/screenshots/frontend/{$style_varname}" . constants::SCREENSHOT_EXT;
					}
					else
					{
						$style_img = "{$this->ext_web_path}assets/screenshots/frontend/default.png";
					}
					break;
			}

			// Remote data
			if (isset($json[$json_tree][$style_varname]))
			{
				$style_name = isset($json[$json_tree][$style_varname]['name']) ? $json[$json_tree][$style_varname]['name'] : $row['style_name'];
				$phpbb_version = isset($json[$json_tree][$style_varname]['phpbb_version']) ? $json[$json_tree][$style_varname]['phpbb_version'] : '';
				$style_price = isset($json[$json_tree][$style_varname]['price']) ? $json[$json_tree][$style_varname]['price'] : 0;
				$style_price_label = isset($json[$json_tree][$style_varname]['price_label']) ? $json[$json_tree][$style_varname]['price_label'] : '';
				$style_download = isset($json[$json_tree][$style_varname]['download']) ? $json[$json_tree][$style_varname]['download'] : '';
				$style_mirror = (isset($json[$json_tree][$style_varname]['mirror']) && is_array($json[$json_tree][$style_varname]['mirror'])) ? $json[$json_tree][$style_varname]['mirror'] : '';
				$style_details = isset($json[$json_tree][$style_varname]['details']) ? $json[$json_tree][$style_varname]['details'] : '';
				$style_support = isset($json[$json_tree][$style_varname]['support']) ? $json[$json_tree][$style_varname]['support'] : '';

				// Build style info
				$style_info = '<strong>' . $this->language->lang('VERSION') . $this->language->lang('COLON') . '</strong> ' . ((isset($json['frontend'][$style_varname]['version']) && !empty($json['frontend'][$style_varname]['version'])) ? $json['frontend'][$style_varname]['version'] : $this->language->lang('UNKNOWN'));
				$style_info .= '<br><strong>' . $this->language->lang('DESIGNER') . $this->language->lang('COLON') . '</strong> ' . ((isset($json[$json_tree][$style_varname]['author']) && !empty($json[$json_tree][$style_varname]['author'])) ? (!empty($json[$json_tree][$style_varname]['author_url']) ? "<a href=\"{$json[$json_tree][$style_varname]['author_url']}\">{$json[$json_tree][$style_varname]['author']}</a>" : $json[$json_tree][$style_varname]['author']) : $this->language->lang('UNKNOWN'));
				$style_info .= '<br><strong>' . $this->language->lang('PRESETS') . $this->language->lang('COLON') . '</strong> ' . (isset($json[$json_tree][$style_varname]['presets']) ? $json[$json_tree][$style_varname]['presets'] : 0);
				$style_info .= '<br><strong>' . $this->language->lang('RESPONSIVE') . $this->language->lang('COLON') . '</strong> ' . ((isset($json[$json_tree][$style_varname]['responsive']) && $json[$json_tree][$style_varname]['responsive']) ? $this->language->lang('YES') : $this->language->lang('NO'));
				$style_info .= '<br><strong>' . $this->language->lang('PRICE') . $this->language->lang('COLON') . '</strong> ' . ((isset($json[$json_tree][$style_varname]['price']) && $json[$json_tree][$style_varname]['price']) ? '<code>' . $json[$json_tree][$style_varname]['price_label'] . '</code>' : '<code class="green">' . $this->language->lang('FREE') . '</code>');
			}
			// Only local data
			else
			{
				$style_name = $row['style_name'];
				$phpbb_version = $row['style_phpbb_version'];
				$style_price = $row['style_price'];
				$style_price_label = $row['style_price_label'];
				$style_download = $row['style_download'];
				$style_mirror = !empty($row['style_mirror']) ? unserialize($row['style_mirror']) : '';
				$style_details = $row['style_details'];
				$style_support = $row['style_support'];

				// Build style info
				$style_info = '<strong>' . $this->language->lang('VERSION') . $this->language->lang('COLON') . '</strong> ' . (!empty($row['style_version']) ? $row['style_version'] : $this->language->lang('UNKNOWN'));
				$style_info .= '<br><strong>' . $this->language->lang('DESIGNER') . $this->language->lang('COLON') . '</strong> ' . (!empty($row['style_author']) ? (!empty($row['style_author_url']) ? "<a href=\"{$row['style_author_url']}\">{$row['style_author']}</a>" : $row['style_author']) : $this->language->lang('UNKNOWN'));
				$style_info .= '<br><strong>' . $this->language->lang('PRESETS') . $this->language->lang('COLON') . '</strong> ' . $row['style_presets'];
				$style_info .= '<br><strong>' . $this->language->lang('RESPONSIVE') . $this->language->lang('COLON') . '</strong> ' . ($row['style_responsive'] ? $this->language->lang('YES') : $this->language->lang('NO'));
				$style_info .= '<br><strong>' . $this->language->lang('PRICE') . $this->language->lang('COLON') . '</strong> ' . ($row['style_price'] ? '<code>' . $row['style_price_label'] . '</code>' : '<code class="green">' . $this->language->lang('FREE') . '</code>');
			}

			// Preview iframe URL
			$preview_url = ($mode == 'acp') ? append_sid("{$this->ext_root_path}app/index.{$this->php_ext}", 's=' . $row['style_path'], false, $this->user->session_id) : append_sid("{$this->phpbb_root_path}index.{$this->php_ext}", 'style=' . $row['style_id']);

			// Mirrors of each style
			$style_mirror_data = array();

			if (is_array($style_mirror) && sizeof($style_mirror))
			{
				$i = 1;
				foreach ($style_mirror as $mirror_url => $mirror_name)
				{
					$style_mirror_data[] = array(
						'name'	=> !empty($mirror_name) ? $mirror_name : $this->language->lang('MIRROR_LABEL', $i),
						'url'	=> $mirror_url,
					);

					$i++;
				}
			}

			// Add each row to $style_data which to be exported to Javascript
			$style_data[$style_varname] = array(
				'name'			=> $style_name,
				'phpbb'			=> $this->language->lang('PHPBB_BADGE', $phpbb_version),
				'phpbb_info'	=> '<strong>' . $this->language->lang('PHPBB_VERSION') . $this->language->lang('COLON') . '</strong> <kbd>' . (!empty($phpbb_version) ? $phpbb_version : $this->language->lang('UNKNOWN')) . '</kbd>',
				'price'			=> $style_price,
				'price_label'	=> ($style_price) ? $style_price_label : $this->language->lang('FREE'),
				'download'		=> $style_download,
				'mirror'		=> sizeof($style_mirror_data) ? $style_mirror_data : null,
				'details'		=> $style_details,
				'support'		=> !empty($style_support) ? $style_support : $this->config['vinabb_stylesdemo_support_url'],
				'img'			=> $style_img,
				'info'			=> $style_info,
				'url'			=> $preview_url,
			);
		}
		$this->db->sql_freeresult($result);

		// Get lang info
		$lang_title = $default_lang_name = $switch_lang_name = '';

		if ($this->config['vinabb_stylesdemo_lang_enable'] && !empty($this->config['vinabb_stylesdemo_lang_switch']) && $this->config['vinabb_stylesdemo_lang_switch'] != $this->config['default_lang'])
		{
			// Get language data from cache
			$lang_data = $this->cache->get('_lang_data');

			if ($lang_data === false)
			{
				$sql = 'SELECT lang_iso, lang_local_name
					FROM ' . LANG_TABLE;
				$result = $this->db->sql_query($sql);

				$lang_data = array();
				while ($row = $this->db->sql_fetchrow($result))
				{
					$lang_data[$row['lang_iso']] = $row['lang_local_name'];
				}
				$this->db->sql_freeresult($result);

				// Write language data to cache
				$this->cache->put('_lang_data', $lang_data);
			}

			$default_lang_name = $lang_data[$this->config['default_lang']];
			$switch_lang_name = $lang_data[$this->config['vinabb_stylesdemo_lang_switch']];
			$lang_title = ($this->user->lang_name == $this->config['default_lang']) ? $this->language->lang('LANG_SWITCH', $default_lang_name, $switch_lang_name) : $this->language->lang('LANG_SWITCH', $switch_lang_name, $default_lang_name);
		}

		// Assign index specific vars
		$this->template->assign_vars(array(
			'STYLE_DATA'		=> json_encode($style_data, JSON_NUMERIC_CHECK),
			'PREFIX_URL'		=> generate_board_url() . '/',
			'LOGO_TEXT'			=> $this->config['vinabb_stylesdemo_logo_text'],
			'AUTO_TOGGLE'		=> ($this->config['vinabb_stylesdemo_auto_toggle']) ? 'true' : 'false',
			'DOWNLOAD_DIRECT'	=> ($this->config['vinabb_stylesdemo_download_direct']) ? 'true' : 'false',
			'PHONE_WIDTH'		=> $this->config['vinabb_stylesdemo_phone_width'],
			'TABLET_WIDTH'		=> $this->config['vinabb_stylesdemo_tablet_width'],

			'DEFAULT_STYLE'		=> constants::DEFAULT_STYLE,
			'CURRENT_LANG'		=> $this->user->lang_name,
			'DEFAULT_LANG'		=> $this->config['default_lang'],
			'DEFAULT_LANG_NAME'	=> $default_lang_name,
			'SWITCH_LANG'		=> $this->config['vinabb_stylesdemo_lang_switch'],
			'SWITCH_LANG_NAME'	=> $switch_lang_name,
			'LANG_TITLE'		=> $lang_title,
			'MODE_TITLE'		=> ($mode == 'acp') ? $this->language->lang('MODE_FRONTEND') : $this->language->lang('MODE_ACP'),

			'EXT_ASSETS_PATH'	=> "{$this->ext_web_path}assets",

			'S_LANG_ENABLE'	=> !empty($lang_title) ? true : false,
			'S_ACP_ENABLE'	=> ($this->config['vinabb_stylesdemo_acp_enable'] && $this->config['vinabb_stylesdemo_num_acp_styles']) ? true : false,

			'U_MODE'	=> $this->helper->route('vinabb_stylesdemo_route', array('mode' => ($mode == 'acp') ? '' : 'acp')),
		));

		return $this->helper->render('demo_body.html', '');
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
		$name = strtolower(trim(utf8_clean_string($name)));
		$name = str_replace(' ', $underscore, $name);

		return $name;
	}
}
