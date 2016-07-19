<?php
/**
* VinaBB Demo Styles
*
* @version 1.07
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/* ADD THIS BLOCK CODE TO INDEX.PHP

define('CUSTOM_LANG', 'vi');
define('CUSTOM_LANG_NAME', 'Vietnamese');

$switch_lang = $request->variable('l', false);
$last_page = $request->variable('z', '');

if ($user->data['user_id'] == ANONYMOUS)
{
	$demo_lang = $request->variable($config['cookie_name'] . '_lang', '', true, \phpbb\request\request_interface::COOKIE);
	$demo_lang = empty($demo_lang) ? $config['default_lang'] : $demo_lang;
}
else
{
	$demo_lang = $user->data['user_lang'];
}

// Need to switch to another language?
if ($switch_lang)
{
	$new_lang = ($demo_lang == 'en') ? CUSTOM_LANG : 'en';

	if ($user->data['user_id'] == ANONYMOUS)
	{
		$user->set_cookie('lang', $new_lang, 0, false);
	}
	else
	{
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_lang = '$new_lang'
			WHERE user_id = " . $user->data['user_id'];
		$db->sql_query($sql);
	}

	redirect(append_sid($phpbb_root_path . $last_page));
}

*/
