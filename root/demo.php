<?php
/**
* VinaBB Demo Styles
*
* @version 1.03
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


/**
* @ignore
*/
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('demo');

define('DEFAULT_STYLE', 'prosilver');
define('CUSTOM_LANG', 'vi');
define('CUSTOM_LANG_NAME', 'Vietnamese');

// Get more online style data
$get_online_data = false;
$get_online_url = '';

// Parameters
$mode = $request->variable('m', '');

// Need to switch to another language?
if ($user->data['user_id'] == ANONYMOUS)
{
	$demo_lang = $request->variable($config['cookie_name'] . '_lang', '', true, \phpbb\request\request_interface::COOKIE);
	$demo_lang = empty($demo_lang) ? $config['default_lang'] : $demo_lang;
}
else
{
	$demo_lang = $user->data['user_lang'];
}

// ACP styles
if ($mode == 'acp')
{
	// Get the list from adm/styles
	if (file_exists($phpbb_admin_path . 'styles/'))
	{
		$style_dirs = array_merge(array(DEFAULT_STYLE), array_diff(scandir($phpbb_admin_path . 'styles/'), array('..', '.')));
		asort($style_dirs);
	}
	// Add the default ACP style in adm/style
	else
	{
		$style_dirs = array(DEFAULT_STYLE);
	}

	foreach ($style_dirs as $style_dir)
	{
		$template->assign_block_vars('styles', array(
			'VARNAME'		=> style_varname_normalize($style_dir, '_'),
			'NAME'			=> $style_dir,
			'TAG'			=> 'phpBB 3.2.0',
			'RESPONSIVE'	=> 1,
			'IMG'			=> "{$web_path}assets/demo/screenshots/example.png",
			'URL'			=> append_sid("{$phpbb_admin_path}index.$phpEx", 's=' . $style_dir, false, $user->session_id),
			'INFO'			=> '<strong>Version:</strong> 1.0.0<br /><strong>Author:</strong> phpBB<br /><strong>Template:</strong> prosilver<br /><strong>Responsive:</strong> Yes<br /><strong>Price:</strong> <kbd>$99</kbd>',
			'NEWEST'		=> 1,
			'BB'			=> 'http://localhost/',
			'GET'			=> 'http://localhost/3012/',
			'BUY'			=> 'http://vnexpress.net/',
			'PRICE'			=> 25,
			'PRICE_LABEL'	=> '$25',
		));
	}
}
// Front-end styles
else
{
	$sql = 'SELECT *
		FROM ' . STYLES_TABLE . '
		WHERE style_active = 1
		ORDER BY style_path';
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		// Style varname
		$style_varname = style_varname_normalize($row['style_path'], '_');

		// Style screenshot
		$style_img = "{$web_path}assets/demo/screenshots/{$style_varname}.png";

		if (!file_exists($style_img))
		{
			$style_img = "{$web_path}assets/demo/screenshots/default.png";
		}

		// Style info
		if ($get_online_data)
		{
			$phpbb_version = '';
			$style_info = '';
			$style_vinabb = $style_download = '';
			$style_price = 0;
		}
		// Only basic info
		else
		{
			// Get data from style.cfg
			$cfg = parse_cfg_file($phpbb_root_path . 'styles/' . $row['style_path'] . '/style.cfg');

			$phpbb_version = $cfg['phpbb_version'];
			$style_info = '<strong>' . $user->lang('VERSION') . $user->lang('COLON') . '</strong> ' . $cfg['style_version'];
			$style_info .= '<br><strong>' . $user->lang('COPYRIGHT') . $user->lang('COLON') . '</strong> ' . $cfg['copyright'];
			$style_vinabb = $style_download = generate_board_url();
			$style_price = rand(0, 1);
			$style_price_label = '$' . $style_price;
		}

		$template->assign_block_vars('styles', array(
			'VARNAME'		=> $style_varname,
			'NAME'			=> $row['style_name'],
			'PHPBB'			=> $user->lang('PHPBB_BADGE', $phpbb_version),
			'PHPBB_INFO'		=> '<strong>' . $user->lang('PHPBB_VERSION') . $user->lang('COLON') . '</strong> <kbd>' . $phpbb_version . '</kbd>',
			'IMG'			=> $style_img,
			'INFO'			=> $style_info,
			'VINABB'			=> $style_vinabb,
			'DOWNLOAD'		=> $style_download,
			'PRICE'			=> $style_price,
			'PRICE_LABEL'	=> ($style_price) ? $style_price_label : $user->lang('FREE'),
			'URL'			=> append_sid("{$phpbb_root_path}index.$phpEx", 'style=' . $row['style_id']),
			'URL_LANG'		=> append_sid("{$phpbb_root_path}index.$phpEx", 'l=1&style=' . $row['style_id']),
		));
	}
	$db->sql_freeresult($result);
}

// Assign index specific vars
$template->assign_vars(array(
	'PREFIX_URL'	=> generate_board_url() . '/',

	'DEFAULT_STYLE'		=> DEFAULT_STYLE,
	'CUSTOM_LANG'		=> CUSTOM_LANG,
	'CUSTOM_LANG_NAME'	=> CUSTOM_LANG_NAME,
	'CURRENT_LANG'		=> $demo_lang,
	'LANG_NAME'			=> ($demo_lang == 'en') ? $user->lang('LANG_ENGLISH', CUSTOM_LANG_NAME) : $user->lang('LANG_CUSTOM', CUSTOM_LANG_NAME),
	'MODE_TITLE'			=> ($mode == 'acp') ? $user->lang('MODE_FRONTEND') : $user->lang('MODE_ACP'),

	'U_MODE'	=> append_sid("{$phpbb_root_path}demo.$phpEx", ($mode == 'acp') ? '' : 'm=acp'),
));

// Output page
page_header($user->lang('DEMO_STYLES'));

$template->set_filenames(array(
	'body' => 'demo_body.html'
));

page_footer();

/*
* Examples:
*	My Style			->	my_style
*	My & Our Style		->	my_and_our_style
*	My&Our Style		->	my_and_our_style
*	My - First Style	->	my_first_style
*	My.First Style		->	my_first_style
*	Aloh@ Style			->	aloha_style
*/
function style_varname_normalize($name, $underscore = '_')
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

?>