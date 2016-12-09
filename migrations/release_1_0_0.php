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
		return array(
			// Config
			array('config.add', array('vinabb_stylesdemo_logo_text', '')),
			array('config.add', array('vinabb_stylesdemo_auto_toggle', 1)),
			array('config.add', array('vinabb_stylesdemo_tablet_width', 768)),
			array('config.add', array('vinabb_stylesdemo_phone_width', 480)),
			array('config.add', array('vinabb_stylesdemo_lang_enable', 0)),
			array('config.add', array('vinabb_stylesdemo_lang_switch', '')),
			array('config.add', array('vinabb_stylesdemo_acp_enable', 0)),
			array('config.add', array('vinabb_stylesdemo_json_enable', 0)),
			array('config.add', array('vinabb_stylesdemo_json_url', '')),
			array('config.add', array('vinabb_stylesdemo_screenshot_type', 0)),
			array('config.add', array('vinabb_stylesdemo_screenshot_width', 1024)),
			array('config.add', array('vinabb_stylesdemo_screenshot_height', 768)),

			// Modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_CAT_STYLES_DEMO')),

			array('module.add', array('acp', 'ACP_CAT_STYLES_DEMO', array(
				'module_basename'	=> '\vinabb\stylesdemo\acp\settings_module',
				'modes'				=> array('settings')
			))),

			// Add the new role "Demo Admin"
			array('permission.role_add', array(constants::ROLE_ADMIN_DEMO, 'a_', constants::ROLE_ADMIN_DEMO . '_DESC')),

			// Set permissions to new role
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_aauth')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_attach')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_authgroups')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_authusers')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_backup')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_ban')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_bbcode')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_board')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_bots')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_clearlogs')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_email')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_extensions')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_fauth')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_forum')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_forumadd')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_forumdel')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_group')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_groupadd')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_groupdel')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_icons')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_jabber')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_language')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_mauth')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_modules')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_names')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_phpinfo', 'role', false)),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_profile')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_prune')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_ranks')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_reasons')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_roles')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_search')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_server')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_styles')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_switchperm', 'role', false)),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_uauth')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_user')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_userdel')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_viewauth')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_viewlogs')),
			array('permission.permission_set', array(constants::ROLE_ADMIN_DEMO, 'a_words'))
		);
	}
}
