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
		$this->language = $language;
		$this->request = $request;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $phpbb_admin_path;
		$this->php_ext = $php_ext;

		$this->ext_path = $this->ext_manager->get_extension_path('vinabb/demostyles', true);
		$this->ext_root_path = $this->path_helper->update_web_root_path($this->ext_path);
	}

	/**
	* Demo controller for route /demo/{name}
	*
	* @param string $name
	* @return Response A Symfony Response object
	*/
	public function handle($mode)
	{
		$this->user->add_lang_ext('vinabb/demostyles', 'demo');

		// Get more online style data
		$get_online_data = true;
		$get_online_url = generate_board_url() . '/assets/demo/styles.json';

		// Parameters
		$mode = $this->request->variable('mode', '');

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

		if ($get_online_data && !empty($get_online_url))
		{
			// Test file URL
			$test = get_headers($get_online_url);

			if (strpos($test[0], '200') !== false)
			{
				// We use cURL here since cURL is faster than file_get_contents()
				$curl = curl_init($get_online_url);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				$raw = curl_exec($curl);
				curl_close($curl);

				// Parse JSON
				$json = json_decode($raw, true);
			}
		}

		// ACP styles
		if ($mode == 'acp')
		{
			// Add the default ACP style in adm/style
			$style_dirs = array(constants::DEFAULT_STYLE);

			// Get the extra ACP style list from adm/styles
			if (file_exists($this->ext_root_path . 'adm/styles/'))
			{
				$scan_dirs = array_diff(scandir("{$this->ext_root_path}adm/styles/"), array('..', '.', '.htaccess'));

				foreach ($scan_dirs as $scan_dir)
				{
					if (is_dir("{$this->ext_root_path}adm/styles/{$scan_dir}/") && file_exists("{$this->ext_root_path}adm/styles/{$scan_dir}/composer.json"))
					{
						$style_dirs[] = $scan_dir;
					}
				}

				// Sort $style_dirs again
				asort($style_dirs);
			}

			foreach ($style_dirs as $style_dir)
			{
				// Style varname
				$style_varname = $this->style_varname_normalize($style_dir);

				// Style screenshot
				$style_img = "{$this->ext_root_path}assets/screenshots/acp/{$style_varname}.png";

				if (!file_exists($style_img))
				{
					$style_img = "{$this->ext_root_path}assets/screenshots/acp/default.png";
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
				// The default ACP style in adm/style
				else if ($style_varname == constants::DEFAULT_STYLE)
				{
					$style_name = constants::DEFAULT_STYLE_NAME;
					$phpbb_version = strtoupper(PHPBB_VERSION);
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $phpbb_version;
					$style_info .= '<br><strong>' . $this->user->lang('COPYRIGHT') . $this->user->lang('COLON') . '</strong> ' . constants::DEFAULT_STYLE_COPYRIGHT;
					$style_vinabb = $style_download = constants::DEFAULT_STYLE_URL;
					$style_price = 0;
					$style_price_label = '';
				}
				// Only local info from composer.json
				else
				{
					// adm/styles/<style_dir_name>/composer.json
					$style_json = json_decode(file_get_contents("{$this->phpbb_admin_path}styles/{$style_dir}/composer.json"), true);

					// How many authors are there?
					if (!function_exists('array_column'))
					{
						$style_authors = array_map(function($element){return $element['name'];}, $style_json['authors']);
					}
					else
					{
						$style_authors = array_column($style_json['authors'], 'name');
					}

					$style_authors = implode(', ', $style_authors);

					$style_name = $style_json['extra']['display-name'];
					$phpbb_version = str_replace(array('<', '=', '>'), '', $style_json['extra']['soft-require']['phpbb/phpbb']);
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $style_json['version'];
					$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $style_authors;
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
					'URL'			=> append_sid("{$this->phpbb_admin_path}index.{$this->php_ext}", 's=' . $style_dir, false, $this->user->session_id),
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
				$style_img = "{$this->ext_root_path}assets/screenshots/frontend/{$style_varname}.png";

				if (!file_exists($style_img))
				{
					$style_img = "{$this->ext_root_path}assets/screenshots/frontend/default.png";
				}

				// Style info
				if (isset($json['frontend'][$style_varname]))
				{
					$phpbb_version = $json['frontend'][$style_varname]['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['version'];
					$style_info .= '<br><strong>' . $this->user->lang('DESIGNER') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['author_name'];
					$style_info .= '<br><strong>' . $this->user->lang('PRESETS') . $this->user->lang('COLON') . '</strong> ' . $json['frontend'][$style_varname]['presets'];
					$style_info .= '<br><strong>' . $this->user->lang('REPONSIVE') . $this->user->lang('COLON') . '</strong> ' . (($json['frontend'][$style_varname]['reponsive'] == 1) ? $user->lang('YES') : $user->lang('NO'));
					$style_info .= '<br><strong>' . $this->user->lang('PRICE') . $this->user->lang('COLON') . '</strong> ' . (($json['frontend'][$style_varname]['price']) ? '<code>' . $json['frontend'][$style_varname]['price_label'] . '</code>' : '<code class=green>' . $user->lang('FREE') . '</code>');
					$style_vinabb = 'http://vinabb.vn/bb/item/' . $json['frontend'][$style_varname]['id'] . '/download';
					$style_download = $json['frontend'][$style_varname]['url'];
					$style_price = $json['frontend'][$style_varname]['price'];
					$style_price_label = $json['frontend'][$style_varname]['price_label'];
				}
				// Only basic info
				else
				{
					$phpbb_version = $cfg['phpbb_version'];
					$style_info = '<strong>' . $this->user->lang('VERSION') . $this->user->lang('COLON') . '</strong> ' . $cfg['style_version'];
					$style_info .= '<br><strong>' . $this->user->lang('COPYRIGHT') . $this->user->lang('COLON') . '</strong> ' . $cfg['copyright'];
					$style_vinabb = $style_download = generate_board_url();
					$style_price = 0;
					$style_price_label = '';
				}

				$this->template->assign_block_vars('styles', array(
					'VARNAME'		=> $style_varname,
					'NAME'			=> $row['style_name'],
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

		// Assign index specific vars
		$this->template->assign_vars(array(
			'PREFIX_URL'	=> generate_board_url() . '/',

			'DEFAULT_STYLE'		=> constants::DEFAULT_STYLE,
			'CUSTOM_LANG'		=> 'vi',
			'CUSTOM_LANG_NAME'	=> 'Vietnamese',
			'CURRENT_LANG'		=> $demo_lang,
			'LANG_NAME'			=> ($demo_lang == 'en') ? $this->user->lang('LANG_ENGLISH', CUSTOM_LANG_NAME) : $this->user->lang('LANG_CUSTOM', CUSTOM_LANG_NAME),
			'MODE_TITLE'		=> ($mode == 'acp') ? $this->user->lang('MODE_FRONTEND') : $this->user->lang('MODE_ACP'),

			'EXT_ASSETS_PATH'	=> "{$this->ext_root_path}assets",

			'S_LANG_ENABLE'	=> $this->config['vinabb_demostyles_lang_enable'],
			'S_ACP_ENABLE'	=> $this->config['vinabb_demostyles_acp_enable'],
			'S_JSON_ENABLE'	=> $this->config['vinabb_demostyles_json_enable'],

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
		$name = str_replace('&', ' & ', $name);
		$name = strtolower(trim($name));
		$name = str_replace(' ', $underscore, $name);
		$name = str_replace('&', ' and ', $name);
		$name = str_replace('-', $underscore, $name);
		$name = str_replace('.', $underscore, $name);
		$name = str_replace('@', 'a', $name);

		return $name;
	}
}