<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

/**
* All language files should use UTF-8 as their encoding
* and the files must not contain a BOM.
*/

$lang = array_merge($lang, [
	'ACP_CAT_STYLES_DEMO'	=> 'Xem thử giao diện',
	'ACP_STYLES_DEMO'		=> 'Thiết lập xem thử giao diện',

	'LOG_STYLES_DEMO_SETTINGS'	=> '<strong>Đã chỉnh thiết lập xem thử giao diện</strong>',

	'ROLE_ADMIN_DEMO'		=> 'Quản trị giả lập',
	'ROLE_ADMIN_DEMO_DESC'	=> 'Chỉ dùng cho chức năng xem thử giao diện.',

	'UNAVAILABLE_IN_DEMO'	=> 'Không lưu thay đổi trong chế độ giả lập.'
]);
