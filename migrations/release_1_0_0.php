<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\migrations;

use phpbb\db\migration\migration;
use vinabb\stylesdemo\includes\constants;

/**
* Migration: release_1_0_0
* To do:	Add config items
*			Add the module category ACP_CAT_STYLES_DEMO
*			Add the setting module
*/
class release_1_0_0 extends migration
{
	public function update_data()
	{
		return [
			// Config
			['config.add', ['vinabb_stylesdemo_logo_text', '']],
			['config.add', ['vinabb_stylesdemo_auto_toggle', 1]],
			['config.add', ['vinabb_stylesdemo_tablet_width', 768]],
			['config.add', ['vinabb_stylesdemo_phone_width', 480]],
			['config.add', ['vinabb_stylesdemo_lang_enable', 0]],
			['config.add', ['vinabb_stylesdemo_lang_switch', '']],
			['config.add', ['vinabb_stylesdemo_acp_enable', 0]],
			['config.add', ['vinabb_stylesdemo_json_enable', 0]],
			['config.add', ['vinabb_stylesdemo_json_url', '']],
			['config.add', ['vinabb_stylesdemo_screenshot_type', 0]],
			['config.add', ['vinabb_stylesdemo_screenshot_width', 1024]],
			['config.add', ['vinabb_stylesdemo_screenshot_height', 768]],

			// Modules
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_CAT_STYLES_DEMO']],

			['module.add', ['acp', 'ACP_CAT_STYLES_DEMO', [
				'module_basename'	=> '\vinabb\stylesdemo\acp\settings_module',
				'modes'				=> ['settings']
			]]],

			// Add the new role "Demo Admin"
			['permission.role_add', [constants::ROLE_ADMIN_DEMO, 'a_', constants::ROLE_ADMIN_DEMO . '_DESC']],

			// Set permissions to new role
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_aauth']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_attach']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_authgroups']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_authusers']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_backup']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_ban']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_bbcode']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_board']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_bots']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_clearlogs']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_email']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_extensions']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_fauth']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_forum']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_forumadd']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_forumdel']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_group']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_groupadd']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_groupdel']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_icons']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_jabber']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_language']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_mauth']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_modules']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_names']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_phpinfo', 'role', false]],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_profile']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_prune']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_ranks']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_reasons']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_roles']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_search']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_server']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_styles']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_switchperm', 'role', false]],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_uauth']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_user']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_userdel']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_viewauth']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_viewlogs']],
			['permission.permission_set', [constants::ROLE_ADMIN_DEMO, 'a_words']]
		];
	}
}
