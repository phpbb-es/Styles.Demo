<?php
/**
* This file is part of the VinaBB Demo Styles package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\demostyles\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
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

	/* @var string */
	protected $phpbb_root_path;

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
								$php_ext)
	{
		$this->db = $db;
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'	=> 'update_lang',
			'core.page_header'	=> 'add_page_header_link',
		);
	}

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
		if ($switch_lang && $this->config['vinabb_demostyles_lang_enable'] && !empty($this->config['vinabb_demostyles_lang_switch']) && $this->config['vinabb_demostyles_lang_switch'] != $this->config['default_lang'])
		{
			$new_lang = ($demo_lang == $this->config['default_lang']) ? $this->config['vinabb_demostyles_lang_switch'] : $this->config['default_lang'];

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

	public function add_page_header_link($event)
	{
		$this->template->assign_vars(array(
			'U_DEMO'	=> $this->helper->route('vinabb_demostyles_route', array('mode' => '')),
		));
	}
}