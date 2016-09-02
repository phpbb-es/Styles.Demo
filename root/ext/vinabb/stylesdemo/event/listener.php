<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use vinabb\stylesdemo\includes\constants;

class listener implements EventSubscriberInterface
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

	/** @var \phpbb\request\request */
    protected $request;

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
	* @param \phpbb\request\request $request
	* @param string $phpbb_root_path
	* @param string $php_ext
	*/
	public function __construct(\phpbb\db\driver\driver_interface $db,
								\phpbb\config\config $config,
								\phpbb\controller\helper $helper,
								\phpbb\template\template $template,
								\phpbb\user $user,
								\phpbb\request\request $request,
								$phpbb_root_path,
								$phpbb_admin_path,
								$php_ext)
	{
		$this->db = $db;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->phpbb_admin_path = $phpbb_admin_path;
		$this->php_ext = $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'				=> 'update_lang',
			'core.user_setup_after'			=> 'redirect_to_demo',
			'core.page_header'				=> 'add_page_header_link',
			'core.adm_page_header_after'	=> 'update_tpl_vars',
		);
	}

	/**
	* Switch languages if the parameter &l=1
	*
	* @param $event
	*/
	public function update_lang($event)
	{
		$switch_lang = $this->request->variable('l', false);
		$last_page = $this->request->variable('z', '');

		if ($this->user->data['user_id'] == ANONYMOUS)
		{
			$demo_lang = $this->request->variable($this->config['cookie_name'] . '_lang', '', true, \phpbb\request\request_interface::COOKIE);
			$demo_lang = empty($demo_lang) ? $this->config['default_lang'] : $demo_lang;
		}
		else
		{
			$demo_lang = $this->user->data['user_lang'];
		}

		// Need to switch to another language?
		if ($switch_lang && $this->config['vinabb_stylesdemo_lang_enable'] && !empty($this->config['vinabb_stylesdemo_lang_switch']) && $this->config['vinabb_stylesdemo_lang_switch'] != $this->config['default_lang'])
		{
			$new_lang = ($demo_lang == $this->config['default_lang']) ? $this->config['vinabb_stylesdemo_lang_switch'] : $this->config['default_lang'];

			if ($this->user->data['user_id'] == ANONYMOUS)
			{
				$this->user->set_cookie('lang', $new_lang, 0, false);
			}
			else
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_lang = '$new_lang'
					WHERE user_id = " . $this->user->data['user_id'];
				$this->db->sql_query($sql);
			}

			redirect(append_sid($this->phpbb_root_path . $last_page));
		}
	}

	/**
	* Redirect to the demo page if any visitors go to our demo board directly
	*
	* @param $event
	*/
	public function redirect_to_demo($event)
	{
		$style = $this->request->variable('style', 0);
		$acp_style = $this->request->variable('s', '');

		if (!$style && !$acp_style && !in_array($this->user->page['page_name'], array("app.{$this->php_ext}/demo/", "app.{$this->php_ext}/demo/acp", "app.{$this->php_ext}/demo/acp/")) && $this->user->data['user_type'] != USER_FOUNDER && !defined('IN_LOGIN'))
		{
			redirect($this->helper->route('vinabb_stylesdemo_route', array('mode' => '')));
		}
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
}
