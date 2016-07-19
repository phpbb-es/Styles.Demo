<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
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
$user->add_lang_ext('vinabb/demostyles', 'demo');

// Some often used variables
$safe_mode = (@ini_get('safe_mode') == '1' || strtolower(@ini_get('safe_mode')) === 'on') ? true : false;
$file_uploads = (@ini_get('file_uploads') == '1' || strtolower(@ini_get('file_uploads')) === 'on') ? true : false;
$module_id = $request->variable('i', '');
$mode = $request->variable('mode', '');

// Custom ACP style
$style = $request->variable('s', '');

if (empty($style) || !file_exists("./styles/{$style}/"))
{
	trigger_error('NO_ACP_STYLES', E_USER_ERROR);
}

$style_dir = "./styles/{$style}/style";

// Set custom style for admin area
$template->set_custom_style(array(
	array(
		'name' 		=> 'adm',
		'ext_path' 	=> 'adm/style/',
	),
), $style_dir);

$template->assign_var('T_ASSETS_PATH', generate_board_url() . '/assets');
$template->assign_var('T_TEMPLATE_PATH', $style_dir);

// Instantiate new module
$module = new p_master();

// Instantiate module system and generate list of available modules
$module->list_modules('acp');

// Select the active module
$module->set_active($module_id, $mode);

// Assign data to the template engine for the list of modules
// We do this before loading the active module for correct menu display in trigger_error
$module->assign_tpl_vars(append_sid(generate_board_url() . "/ext/vinabb/demostyles/app/index.$phpEx"));

// Load and execute the relevant module
$module->load_active();

// Generate the page
adm_page_header($module->get_page_title());

$template->set_filenames(array(
	'body' => $module->get_tpl_name(),
));

adm_page_footer();
