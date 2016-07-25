<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

/**
* Welcome you to APP: Admin Preview Panel
*/
define('IN_PHPBB', true);
define('IN_ADMIN', true);
define('ADMIN_START', true);
define('NEED_SID', true);

// Include files
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_acp.' . $phpEx);
require($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
require($phpbb_root_path . 'includes/functions_module.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');
$language->add_lang_ext('vinabb/stylesdemo', 'demo');

// Do not access this file once ACP mode is disabled
if (!$config['vinabb_stylesdemo_acp_enable'])
{
	trigger_error('ACP_STYLES_DISABLED', E_USER_WARNING);
}

// Some often used variables
$safe_mode = (@ini_get('safe_mode') == '1' || strtolower(@ini_get('safe_mode')) === 'on') ? true : false;
$file_uploads = (@ini_get('file_uploads') == '1' || strtolower(@ini_get('file_uploads')) === 'on') ? true : false;
$module_id = $request->variable('i', '');
$mode = $request->variable('mode', '');

// To be admin? So easy ;)
if ($user->data['user_id'] == ANONYMOUS && !$user->data['session_admin'])
{
	$sql = 'UPDATE ' . SESSIONS_TABLE . "
		SET session_admin = 1
		WHERE session_id = '" . $db->sql_escape($user->session_id) . "'";
	$db->sql_query($sql);
}

// Custom ACP style
$style = $request->variable('s', '');

if (empty($style) || !file_exists("./styles/{$style}/"))
{
	trigger_error('NO_ACP_STYLES', E_USER_WARNING);
}

$style_dir = "./styles/{$style}/style";

// Set custom style for admin area
$template->set_custom_style(array(
	array(
		'name' 		=> 'adm',
		'ext_path' 	=> 'adm/style/',
	),
), $style_dir);

$template->assign_vars(array(
	'T_ASSETS_PATH'		=> generate_board_url() . '/assets',
	'T_TEMPLATE_PATH'	=> $style_dir,
	'S_PREVIEW_ACP'		=> true
));

// Instantiate new module
$module = new p_master();

// Instantiate module system and generate list of available modules
$module->list_modules('acp');

// Select the active module
$module->set_active($module_id, $mode);

// Assign data to the template engine for the list of modules
// We do this before loading the active module for correct menu display in trigger_error
$module->assign_tpl_vars(append_sid(generate_board_url() . "/ext/vinabb/stylesdemo/app/index.$phpEx"));

// Load and execute the relevant module
$module->load_active();

// Generate the page
adm_page_header($module->get_page_title());

$template->set_filenames(array(
	'body' => $module->get_tpl_name()
));

adm_page_footer();
