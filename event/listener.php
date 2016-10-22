<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
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

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var \phpbb\path_helper */
	protected $path_helper;

	/** @var string */
	protected $phpbb_root_path;

	/** @var string */
	protected $phpbb_admin_path;

	/** @var string */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\cache\driver\driver_interface $cache
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $helper
	* @param \phpbb\template\template $template
	* @param \phpbb\user $user
	* @param \phpbb\language\language $language
	* @param \phpbb\extension\manager $ext_manager
	* @param \phpbb\path_helper $path_helper
	* @param string $phpbb_root_path
	* @param string $php_ext
	*/
	public function __construct(\phpbb\cache\driver\driver_interface $cache,
								\phpbb\config\config $config,
								\phpbb\controller\helper $helper,
								\phpbb\template\template $template,
								\phpbb\user $user,
								\phpbb\language\language $language,
								\phpbb\extension\manager $ext_manager,
								\phpbb\path_helper $path_helper,
								$phpbb_root_path,
								$phpbb_admin_path,
								$php_ext)
	{
		$this->cache = $cache;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->language = $language;
		$this->ext_manager = $ext_manager;
		$this->path_helper = $path_helper;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $phpbb_admin_path;
		$this->php_ext = $php_ext;

		$this->ext_root_path = $this->ext_manager->get_extension_path('vinabb/stylesdemo', true);
		$this->ext_web_path = $this->path_helper->update_web_root_path($this->ext_root_path);
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header'				=> 'add_page_header_link',
			'core.adm_page_header_after'	=> 'update_tpl_vars',
			'core.add_log'					=> 'purge_lang_cache',
		);
	}

	/**
	* Add link variable to template files
	*
	* @param $event
	*/
	public function add_page_header_link($event)
	{
		$this->template->assign_vars(array(
			'U_DEMO'	=> $this->helper->route('vinabb_stylesdemo_route', array('mode' => ''))
		));
	}

	/**
	* Adjust some template variables
	*
	* @param $event
	*/
	public function update_tpl_vars($event)
	{
		$this->template->assign_vars(array(
			'ROOT_PATH'			=> $this->phpbb_root_path,
			'ADMIN_ROOT_PATH'	=> $this->phpbb_admin_path,
			'PREFIX_URL'		=> generate_board_url() . '/' . $this->phpbb_admin_path,
			'PREFIX_URL_ALT'	=> generate_board_url(),

			'ICON_ENABLE'	=> '<img src="' . $this->ext_web_path . 'adm/images/icon_disabled.gif" alt="' . $this->language->lang('ENABLE') . '" title="' . $this->language->lang('ENABLE') . '">',
			'ICON_DISABLE'	=> '<img src="' . $this->ext_web_path . 'adm/images/icon_enabled.gif" alt="' . $this->language->lang('DISABLE') . '" title="' . $this->language->lang('DISABLE') . '">',

			'S_GUEST'	=> ($this->user->data['user_id'] == ANONYMOUS) ? true : false,

			'U_LOGOUT'		=> ($this->user->data['user_id'] == ANONYMOUS) ? '#' : append_sid("{$this->phpbb_root_path}ucp.{$this->php_ext}", 'mode=logout'),
			'U_ADM_LOGOUT'	=> ($this->user->data['user_id'] == ANONYMOUS) ? '#' : append_sid("{$this->phpbb_admin_path}index.{$this->php_ext}", 'action=admlogout'),
			'U_ADM_INDEX'	=> '#',
			'U_INDEX'		=> append_sid("{$this->phpbb_root_path}index.{$this->php_ext}"),

			'T_SMILIES_PATH'		=> "{$this->phpbb_root_path}{$this->config['smilies_path']}/",
			'T_AVATAR_PATH'			=> "{$this->phpbb_root_path}{$this->config['avatar_path']}/",
			'T_AVATAR_GALLERY_PATH'	=> "{$this->phpbb_root_path}{$this->config['avatar_gallery_path']}/",
			'T_ICONS_PATH'			=> "{$this->phpbb_root_path}{$this->config['icons_path']}/",
			'T_RANKS_PATH'			=> "{$this->phpbb_root_path}{$this->config['ranks_path']}/",
			'T_UPLOAD_PATH'			=> "{$this->phpbb_root_path}{$this->config['upload_path']}/",
		));
	}

	/**
	* Refresh language data when install/uninstall a language pack
	*
	* @param $event
	*/
	public function purge_lang_cache($event)
	{
		if ($event['log_operation'] == 'LOG_LANGUAGE_PACK_INSTALLED' || $event['log_operation'] == 'LOG_LANGUAGE_PACK_DELETED')
		{
			$this->cache->destroy('_lang_data');
		}
	}
}
