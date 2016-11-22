<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\decorated;

class user extends \phpbb\user
{
	/**
	* Setup basic user-specific items (style, language...)
	*
	* Copied from phpBB 3.2.0-RC1 with 4 changes:
	* REMEMBER TO UPDATE CODE CHANGES FOR LATER PHPBB VERSIONS IF NEEDED
	*
	* @param bool $lang_set
	* @param bool $style_id
	*/
	function setup($lang_set = false, $style_id = false)
	{
		global $db, $request, $template, $config, $auth, $phpEx, $phpbb_root_path, $cache;
		global $phpbb_dispatcher, $phpbb_container;

		$this->language->set_default_language($config['default_lang']);

		if ($this->data['user_id'] != ANONYMOUS)
		{
			$user_lang_name = (file_exists($this->lang_path . $this->data['user_lang'] . "/common.$phpEx")) ? $this->data['user_lang'] : basename($config['default_lang']);
			$user_date_format = $this->data['user_dateformat'];
			$user_timezone = $this->data['user_timezone'];
		}
		else
		{
			$lang_override = $request->variable('language', '');

			if ($lang_override)
			{
				$this->set_cookie('lang', $lang_override, 0, false);
			}
			else
			{
				$lang_override = $request->variable($config['cookie_name'] . '_lang', '', true, \phpbb\request\request_interface::COOKIE);
			}

			if ($lang_override)
			{
				$use_lang = basename($lang_override);
				$user_lang_name = (file_exists($this->lang_path . $use_lang . "/common.$phpEx")) ? $use_lang : basename($config['default_lang']);
				$this->data['user_lang'] = $user_lang_name;
			}
			else
			{
				$user_lang_name = basename($config['default_lang']);
			}

			$user_date_format = $config['default_dateformat'];
			$user_timezone = $config['board_timezone'];
		}

		// Switch languages if the parameter &l=1
		$switch_lang = $request->variable('l', false);
		$last_page = str_replace('&amp;', '&', $request->variable('z', ''));

		if ($this->data['user_id'] == ANONYMOUS)
		{
			$demo_lang = $request->variable($config['cookie_name'] . '_lang', '', true, \phpbb\request\request_interface::COOKIE);
			$demo_lang = empty($demo_lang) ? $config['default_lang'] : $demo_lang;
		}
		else
		{
			$demo_lang = $this->data['user_lang'];
		}

		// Need to switch to another language?
		if ($switch_lang && $config['vinabb_stylesdemo_lang_enable'] && !empty($config['vinabb_stylesdemo_lang_switch']) && $config['vinabb_stylesdemo_lang_switch'] != $config['default_lang'])
		{
			$new_lang = ($demo_lang == $config['default_lang']) ? $config['vinabb_stylesdemo_lang_switch'] : $config['default_lang'];

			if ($this->data['user_id'] == ANONYMOUS)
			{
				$this->set_cookie('lang', $new_lang, 0, false);
			}
			else
			{
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_lang = '$new_lang'
					WHERE user_id = " . $this->data['user_id'];
				$db->sql_query($sql);
			}

			$response = new \Symfony\Component\HttpFoundation\RedirectResponse(
				append_sid($phpbb_root_path . $last_page),
				301
			);
			$response->send();
		}

		$user_data = $this->data;
		$lang_set_ext = array();

		/**
		* Event to load language files and modify user data on every page
		*
		* @event core.user_setup
		* @var	array	user_data			Array with user's data row
		* @var	string	user_lang_name		Basename of the user's langauge
		* @var	string	user_date_format	User's date/time format
		* @var	string	user_timezone		User's timezone, should be one of
		*							http://www.php.net/manual/en/timezones.php
		* @var	mixed	lang_set			String or array of language files
		* @var	array	lang_set_ext		Array containing entries of format
		* 					array(
		* 						'ext_name' => (string) [extension name],
		* 						'lang_set' => (string|array) [language files],
		* 					)
		* 					For performance reasons, only load translations
		* 					that are absolutely needed globally using this
		* 					event. Use local events otherwise.
		* @var	mixed	style_id			Style we are going to display
		* @since 3.1.0-a1
		*/
		$vars = array(
			'user_data',
			'user_lang_name',
			'user_date_format',
			'user_timezone',
			'lang_set',
			'lang_set_ext',
			'style_id',
		);
		extract($phpbb_dispatcher->trigger_event('core.user_setup', compact($vars)));

		$this->data = $user_data;
		$this->lang_name = $user_lang_name;
		$this->date_format = $user_date_format;

		$this->language->set_user_language($user_lang_name);

		try
		{
			$this->timezone = new \DateTimeZone($user_timezone);
		}
		catch (\Exception $e)
		{
			// If the timezone the user has selected is invalid, we fall back to UTC.
			$this->timezone = new \DateTimeZone('UTC');
		}

		$this->language->add_lang($lang_set);
		unset($lang_set);

		foreach ($lang_set_ext as $ext_lang_pair)
		{
			$this->language->add_lang($ext_lang_pair['lang_set'], $ext_lang_pair['ext_name']);
		}
		unset($lang_set_ext);

		// Stop here if the fake ACP URL .../ext/vinabb/stylesdemo/app/index.php... was changed to .../adm/index.php... :-/
		if ($this->data['user_id'] == ANONYMOUS && defined('ADMIN_START') && !defined('FAKE_ACP'))
		{
			send_status_line(403, 'Forbidden');
			trigger_error($this->language->lang('NO_ADMIN'), E_USER_ERROR);
		}

		$acp_style_request = $request->variable('s', '');

		if ($acp_style_request && defined('ADMIN_START'))
		{
			global $SID, $_EXTRA_URL;

			$SID .= '&amp;s=' . $acp_style_request;
			$_EXTRA_URL = array('s=' . $acp_style_request);
		}

		$style_request = $request->variable('style', 0);

		if ($style_request && !defined('ADMIN_START'))
		{
			global $SID, $_EXTRA_URL;

			$style_id = $style_request;
			$SID .= '&amp;style=' . $style_id;
			$_EXTRA_URL = array('style=' . $style_id);
		}
		else
		{
			// Set up style
			$style_id = ($style_id) ? $style_id : ((!$config['override_user_style']) ? $this->data['user_style'] : $config['default_style']);
		}

		$sql = 'SELECT *
			FROM ' . STYLES_TABLE . " s
			WHERE s.style_id = $style_id";
		$result = $db->sql_query($sql, 3600);
		$this->style = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		// Fallback to user's standard style
		if (!$this->style && $style_id != $this->data['user_style'])
		{
			$style_id = $this->data['user_style'];

			$sql = 'SELECT *
				FROM ' . STYLES_TABLE . " s
				WHERE s.style_id = $style_id";
			$result = $db->sql_query($sql, 3600);
			$this->style = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		}

		// User has wrong style
		if (!$this->style && $style_id == $this->data['user_style'])
		{
			$style_id = $this->data['user_style'] = $config['default_style'];

			$sql = 'UPDATE ' . USERS_TABLE . "
				SET user_style = $style_id
				WHERE user_id = {$this->data['user_id']}";
			$db->sql_query($sql);

			$sql = 'SELECT *
				FROM ' . STYLES_TABLE . " s
				WHERE s.style_id = $style_id";
			$result = $db->sql_query($sql, 3600);
			$this->style = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
		}

		if (!$this->style)
		{
			trigger_error('NO_STYLE_DATA', E_USER_ERROR);
		}

		// Now parse the cfg file and cache it
		$parsed_items = $cache->obtain_cfg_items($this->style);

		$check_for = array(
			'pagination_sep'    => (string) ', '
		);

		foreach ($check_for as $key => $default_value)
		{
			$this->style[$key] = (isset($parsed_items[$key])) ? $parsed_items[$key] : $default_value;

			settype($this->style[$key], gettype($default_value));

			/*if (is_string($default_value))
			{
				$this->style[$key] = htmlspecialchars($this->style[$key]);
			}*/
		}

		$template->set_style();

		$this->img_lang = $this->lang_name;

		// Call phpbb_user_session_handler() in case external application want to "bend" some variables or replace classes...
		// After calling it we continue script execution...
		phpbb_user_session_handler();

		// Redirect to the demo page if any visitors go to our demo board directly
		if (!$style_request && !$acp_style_request && !in_array($this->page['page_name'], array("app.$phpEx/demo/", "app.$phpEx/demo/acp", "app.$phpEx/demo/acp/")) && $this->data['user_type'] != USER_FOUNDER && !defined('IN_LOGIN'))
		{
			$helper = $phpbb_container->get('controller.helper');

			$response = new \Symfony\Component\HttpFoundation\RedirectResponse(
				$helper->route('vinabb_stylesdemo_route', array('mode' => '')),
				301
			);
			$response->send();
		}

		/**
		* Execute code at the end of user setup
		*
		* @event core.user_setup_after
		* @since 3.1.6-RC1
		*/
		$phpbb_dispatcher->dispatch('core.user_setup_after');

		// If this function got called from the error handler we are finished here.
		if (defined('IN_ERROR_HANDLER'))
		{
			return;
		}

		// Disable board if the install/ directory is still present
		// For the brave development army we do not care about this, else we need to comment out this everytime we develop locally
		if (!defined('DEBUG') && !defined('ADMIN_START') && !defined('IN_INSTALL') && !defined('IN_LOGIN') && file_exists($phpbb_root_path . 'install') && !is_file($phpbb_root_path . 'install'))
		{
			// Adjust the message slightly according to the permissions
			if ($auth->acl_gets('a_', 'm_') || $auth->acl_getf_global('m_'))
			{
				$message = 'REMOVE_INSTALL';
			}
			else
			{
				$message = (!empty($config['board_disable_msg'])) ? $config['board_disable_msg'] : 'BOARD_DISABLE';
			}

			trigger_error($message);
		}

		// Is board disabled and user not an admin or moderator?
		if ($config['board_disable'] && !defined('IN_LOGIN') && !defined('SKIP_CHECK_DISABLED') && ((!$auth->acl_gets('a_', 'm_') && !$auth->acl_getf_global('m_')) || $this->data['user_id'] == ANONYMOUS))
		{
			if ($this->data['is_bot'])
			{
				send_status_line(503, 'Service Unavailable');
			}

			$message = (!empty($config['board_disable_msg'])) ? $config['board_disable_msg'] : 'BOARD_DISABLE';
			trigger_error($message);
		}

		// Is load exceeded?
		if ($config['limit_load'] && $this->load !== false)
		{
			if ($this->load > floatval($config['limit_load']) && !defined('IN_LOGIN') && !defined('IN_ADMIN'))
			{
				// Set board disabled to true to let the admins/mods get the proper notification
				$config['board_disable'] = '1';

				if ((!$auth->acl_gets('a_', 'm_') && !$auth->acl_getf_global('m_')) || $this->data['user_id'] == ANONYMOUS)
				{
					if ($this->data['is_bot'])
					{
						send_status_line(503, 'Service Unavailable');
					}

					trigger_error('BOARD_UNAVAILABLE');
				}
			}
		}

		if (isset($this->data['session_viewonline']))
		{
			// Make sure the user is able to hide his session
			if (!$this->data['session_viewonline'])
			{
				// Reset online status if not allowed to hide the session...
				if (!$auth->acl_get('u_hideonline'))
				{
					$sql = 'UPDATE ' . SESSIONS_TABLE . '
						SET session_viewonline = 1
						WHERE session_user_id = ' . $this->data['user_id'];
					$db->sql_query($sql);

					$this->data['session_viewonline'] = 1;
				}
			}
			else if (!$this->data['user_allow_viewonline'])
			{
				// the user wants to hide and is allowed to  -> cloaking device on.
				if ($auth->acl_get('u_hideonline'))
				{
					$sql = 'UPDATE ' . SESSIONS_TABLE . '
						SET session_viewonline = 0
						WHERE session_user_id = ' . $this->data['user_id'];
					$db->sql_query($sql);

					$this->data['session_viewonline'] = 0;
				}
			}
		}

		// Does the user need to change their password? If so, redirect to the
		// ucp profile reg_details page ... of course do not redirect if we're already in the ucp
		if (!defined('IN_ADMIN') && !defined('ADMIN_START') && $config['chg_passforce'] && !empty($this->data['is_registered']) && $auth->acl_get('u_chgpasswd') && $this->data['user_passchg'] < time() - ($config['chg_passforce'] * 86400))
		{
			if (strpos($this->page['query_string'], 'mode=reg_details') === false && $this->page['page_name'] != "ucp.$phpEx")
			{
				redirect(append_sid("{$phpbb_root_path}ucp.$phpEx", 'i=profile&amp;mode=reg_details'));
			}
		}

		$this->is_setup_flag = true;

		return;
	}
}
