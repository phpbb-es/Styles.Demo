<?php
/**
* VinaBB Demo Styles
*
* @version 1.02
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*
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

$mode = $request->variable('m', '');

// ACP styles
if ($mode == 'acp')
{
	// Get the list from adm/styles
	if (file_exists($phpbb_admin_path . 'styles/'))
	{
		$style_dirs = array_merge(array('prosilver'), array_diff(scandir($phpbb_admin_path . 'styles/'), array('..', '.')));
		asort($style_dirs);
	}
	// prosilver is the name of default ACP style in adm/style
	else
	{
		$style_dirs = array('prosilver');
	}

	foreach ($style_dirs as $style_dir)
	{
		$template->assign_block_vars('styles', array(
			'VARNAME'		=> style_varname_normalize($style_dir, '_'),
			'NAME'			=> $style_dir,
			'TAG'			=> '<i class="icon-star"></i> phpBB 3.2.0',
			'RESPONSIVE'	=> 1,
			'IMG'			=> "{$web_path}assets/demo/screenshots/example.jpg",
			'URL'			=> append_sid("{$phpbb_admin_path}index.$phpEx", 's=' . $style_dir, false, $user->session_id),
			'INFO'			=> '<strong>Version:</strong> 1.0.0<br /><strong>Author:</strong> phpBB<br /><strong>Template:</strong> prosilver<br /><strong>Responsive:</strong> Yes<br /><strong>Price:</strong> <kbd>$99</kbd>',
			'NEWEST'		=> 1,
			'BB'			=> 'http://localhost/',
			'GET'			=> 'http://localhost/3012/',
			'BUY'			=> 'http://vnexpress.net/',
			'PRICE'			=> 25,
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
		// Get data from style.cfg
		$cfg = parse_cfg_file($phpbb_root_path . 'styles/' . $row['style_path'] . '/style.cfg');

		$template->assign_block_vars('styles', array(
			'VARNAME'		=> style_varname_normalize($row['style_path'], '_'),
			'NAME'			=> $row['style_name'],
			'TAG'			=> '<i class="fa fa-star"></i> phpBB ' . $cfg['phpbb_version'],
			'RESPONSIVE'	=> ($row['style_id'] % 2 === 0) ? 1 : 0,
			'IMG'			=> "{$web_path}assets/demo/screenshots/example.jpg",
			'URL'			=> append_sid("{$phpbb_root_path}index.$phpEx", 'style=' . $row['style_id']),
			'INFO'			=> "<strong>Version:</strong> {$cfg['style_version']}<br><strong>Author:</strong> {$row['style_copyright']}<br><strong>Price:</strong> <kbd>$99</kbd>",
			'NEWEST'		=> ($row['style_id'] > 100) ? 1 : 0,
			'BB'			=> 'http://localhost/',
			'GET'			=> 'http://localhost/3012/',
			'BUY'			=> 'http://vnexpress.net/',
			'PRICE'			=> ($row['style_id'] < 100) ? 10 : 25,
		));
	}
	$db->sql_freeresult($result);
}

// Assign index specific vars
$template->assign_vars(array(
	'SWITCH_TITLE'	=> ($mode == 'acp') ? $user->lang('SWITCH_FRONTEND') : $user->lang('SWITCH_ACP'),
	'U_SWITCH'		=> append_sid("{$phpbb_root_path}demo.$phpEx", ($mode == 'acp') ? '' : 'm=acp'),
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