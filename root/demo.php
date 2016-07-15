<?php
/**
* VinaBB Demo Styles
*
* @version 1.00
* @copyright (c) 2014 nedka
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* NOTE: Enable style preview for everyone
*
* For phpBB 3.0.x
*
* Open: includes/session.php
* Find:
*	if (!empty($_GET['style']) && $auth->acl_get('a_styles') && !defined('ADMIN_START'))
* Replace with:
*	if (!empty($_GET['style']) && !defined('ADMIN_START'))
*
* For phpBB 3.1.x
*
* Open: phpbb/user.php
* Find:
*	if ($style_request && (!$config['override_user_style'] || $auth->acl_get('a_styles')) && !defined('ADMIN_START'))
* Replace with:
*	if ($style_request && !defined('ADMIN_START'))
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
$user->setup();

$mode = $request->variable('m', '');



if ($mode == 'acp')
{
	// $phpbb_adm_relative_path
	// append_sid("{$phpbb_admin_path}index.$phpEx", false, true, $user->session_id)
	$style_dirs = array_merge(array('prosilver'), array_diff(scandir($phpbb_admin_path . 'styles/'), array('..', '.')));
	asort($style_dirs);

	foreach ($style_dirs as $style_dir)
	{
		$template->assign_block_vars('styles', array(
			//'ID'			=> $row['style_id'],
			'VARNAME'		=> style_varname_normalize($style_dir, '_'),
			'NAME'			=> $style_dir,
			'TAG'			=> '<i class="icon-star"></i> phpBB 3.2.0',
			'RESPONSIVE'	=> 1,
			'IMG'			=> 'http://www.rockettheme.com/images/phpbb/styles/osmosis/master.jpg',
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
else
{
	$sql = 'SELECT *
		FROM ' . STYLES_TABLE . '
		WHERE style_active = 1
		ORDER BY style_name';
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('styles', array(
			'ID'			=> $row['style_id'],
			'VARNAME'		=> style_varname_normalize($row['style_name'], '_'),
			'NAME'			=> $row['style_name'],
			'TAG'			=> '<i class="icon-star"></i> phpBB 3.2.0',
			'RESPONSIVE'	=> ($row['style_id'] % 2 === 0) ? 1 : 0,
			'IMG'			=> 'http://www.rockettheme.com/images/phpbb/styles/osmosis/master.jpg',
			'URL'			=> append_sid("{$phpbb_root_path}index.$phpEx", 'style=' . $row['style_id']),
			'INFO'			=> '<strong>Version:</strong> 1.0.0<br /><strong>Author:</strong> ' . $row['style_copyright'] . '<br /><strong>Template:</strong> prosilver<br /><strong>Responsive:</strong> Yes<br /><strong>Price:</strong> <kbd>$99</kbd>',
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
	'SWITCH_TITLE'	=> ($mode == 'acp') ? 'Switch to Frontend Demo' : 'Switch to ACP Demo',
	'U_SWITCH'		=> append_sid("{$phpbb_root_path}demo.$phpEx", ($mode == 'acp') ? '' : 'm=acp'),
));

// Output page
page_header('');

$template->set_filenames(array(
	'body' => 'demo_body.html')
);

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