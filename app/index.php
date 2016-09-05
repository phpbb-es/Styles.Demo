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
define('FAKE_ACP', true);

// Include files
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './../../../../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_acp.' . $phpEx);
require($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
require('./../includes/functions_module.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('acp/common');

// Do not access this file once ACP mode is disabled
if (!$config['vinabb_stylesdemo_acp_enable'])
{
	trigger_error('NO_ADMIN', E_USER_ERROR);
}

// To be admin? So easy ;)
if ($user->data['user_id'] == ANONYMOUS && !$user->data['session_admin'])
{
	$sql = 'UPDATE ' . SESSIONS_TABLE . "
		SET session_admin = 1
		WHERE session_id = '" . $db->sql_escape($user->session_id) . "'";
	$db->sql_query($sql);
}

// Some often used variables
$safe_mode = (@ini_get('safe_mode') == '1' || strtolower(@ini_get('safe_mode')) === 'on') ? true : false;
$file_uploads = (@ini_get('file_uploads') == '1' || strtolower(@ini_get('file_uploads')) === 'on') ? true : false;
$module_id = $request->variable('i', '');
$mode = $request->variable('mode', '');
$action = $request->variable('action', '');
$style = $request->variable('s', '');

if (empty($style) || !file_exists("./styles/{$style}/"))
{
	trigger_error('NO_STYLE_DATA', E_USER_ERROR);
}

// If a module id starts with "-", it is a module from extensions
// Here we check only original ACP modules in phpBB core
$i = (substr($module_id, 0, 1) == '-') ? '' : ((substr($module_id, 0, 4) == 'acp_') ? $module_id : 'acp_' . $module_id);

// Again, no changes saved in demo mode :D
$no_submit = false;

// Prevent all submit actions from the guest user
if ($user->data['user_id'] == ANONYMOUS && (
	// Restricted modules
	($i == 'acp_php_info' || $i == 'acp_send_statistics')
	// Common actions
	|| in_array($action, array('enable', 'disable', 'enable_pre', 'disable_pre', 'activate', 'deactivate', 'move_up', 'move_down', 'install', 'uninstall', 'sync', 'progress_bar', 'create', 'delete', 'delete_data', 'delete_data_pre'))
	// Common input names of the main submit button
	|| $request->is_set_post('submit')
	|| $request->is_set_post('update')
	|| $request->is_set_post('save')
	// acp_attachments.html
	|| $request->is_set_post('securesubmit')
	|| $request->is_set_post('unsecuresubmit')
	|| $request->is_set_post('add_extension_check')
	|| $request->is_set_post('action_stats')
	// acp_ban.html
	|| $request->is_set_post('bansubmit')
	|| $request->is_set_post('unbansubmit')
	// acp_board.php
	|| $request->is_set_post('allow_quick_reply_enable')
	// acp_captcha.html
	|| $request->is_set_post('main_submit')
	// acp_database.html
	|| $request->is_set_post('delete')
	|| $request->is_set_post('download')
	// acp_disallow.html
	|| $request->is_set_post('disallow')
	|| $request->is_set_post('allow')
	// acp_ext_delete_data.html
	|| $request->is_set_post('delete_data')
	// acp_ext_disable.html
	|| $request->is_set_post('disable')
	// acp_ext_enable.html
	|| $request->is_set_post('enable')
	// acp_groups.html
	|| $request->is_set_post('addusers')
	// acp_icons.html, acp_permission_roles.html
	|| ($request->is_set_post('add') && ($i == 'acp_icons' || $i == 'acp_permission_roles'))
	// acp_icons.html
	|| $request->is_set_post('import')
	|| $request->is_set_post('edit')
	// acp_language.html
	|| $request->is_set_post('update_details')
	// acp_logs.html, acp_users_feedback.html, acp_users_warnings.html
	|| $request->is_set_post('delall')
	// acp_logs.html, acp_users_feedback.html, acp_users_warnings.html, acp_users.html
	|| $request->is_set_post('delmarked')
	// acp_main.html
	|| $request->is_set_post('action_online')
	|| $request->is_set_post('action_date')
	|| $request->is_set_post('action_stats')
	|| $request->is_set_post('action_user')
	|| $request->is_set_post('action_db_track')
	|| $request->is_set_post('action_purge_sessions')
	|| $request->is_set_post('action_purge_cache')
	// acp_modules.html
	|| $request->is_set_post('quickadd')
	// acp_permissions.html
	|| $request->is_set_post('action[delete]')
	|| $request->is_set_post('action[apply_all_permissions]')
	|| $request->is_set_post('submit_edit_options')
	|| $request->is_set_post('submit_add_options')
	//acp_styles.html
	|| $request->is_set_post('install')
	|| $request->is_set_post('uninstall')
	|| $request->is_set_post('activate')
	|| $request->is_set_post('deactivate')
	// acp_styles.html, confirm_bbcode.html, confirm_body_prune.html, confirm_body.html
	|| $request->is_set_post('confirm')
	// acp_users.html
	|| $request->is_set_post('submituser')
))
{
	$no_submit = true;
}

// New style directory
$style_dir = "./styles/{$style}/style";

// Set custom style for admin area
$template->set_custom_style(array(
	array(
		'name' 		=> 'adm',
		'ext_path' 	=> 'adm/style/',
	),
), $style_dir);

// Template variables
$template->assign_vars(array(
	'STYLE_DIRNAME'	=> $style,

	'T_ASSETS_PATH'		=> generate_board_url() . '/assets',
	'T_TEMPLATE_PATH'	=> $style_dir,
	'T_IMAGES_PATH'		=> "./styles/{$style}/images",

	'S_PREVIEW_ACP'	=> true
));

// Instantiate new module
$module = new \vinabb\stylesdemo\includes\fake_p_master();

// Instantiate module system and generate list of available modules
$module->list_modules('acp');

// Select the active module
$module->set_active($module_id, $mode);

// Assign data to the template engine for the list of modules
// We do this before loading the active module for correct menu display in trigger_error
$module->assign_tpl_vars(append_sid(generate_board_url() . "/ext/vinabb/stylesdemo/app/index.$phpEx"));

// Load and execute the relevant module
if ($no_submit)
{
	trigger_error('UNAVAILABLE_IN_DEMO', E_USER_WARNING);
}
else
{
	$module->load_active(false, false, true, $style);
}

// Generate the page
adm_page_header($module->get_page_title());

$template->set_filenames(array(
	'body' => $module->get_tpl_name()
));

adm_page_footer();
