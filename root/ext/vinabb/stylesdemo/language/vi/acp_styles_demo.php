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
	$lang = array();
}

/**
* All language files should use UTF-8 as their encoding
* and the files must not contain a BOM.
*/

$lang = array_merge($lang, array(
	'ACP_STYLES_DEMO_EXPLAIN'	=> 'Cảm ơn bạn đã dùng thử “Styles Demo”. Phiên bản ổn định hơn đang được phát triển tại <a href="https://github.com/VinaBB/Styles.Demo">GitHub</a>.',

	'DEFAULT_LANGUAGE'	=> 'Ngôn ngữ mặc định',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'Không có thêm ngôn ngữ khác để chọn.',

	'SELECT_LANGUAGE'					=> 'Chọn ngôn ngữ',
	'STYLES_DEMO_ACP_ENABLE'			=> 'Cho phép xem thử giao diện quản trị',
	'STYLES_DEMO_ACP_ENABLE_EXPLAIN'	=> 'Nếu bật, khách sẽ được cấp quyền quản trị để truy cập vào bảng quản trị giả. Tuy nhiên, không có thay đổi nào được lưu trong chế độ giả lập này.',
	'STYLES_DEMO_JSON_ENABLE'			=> 'Lấy dữ liệu giao diện từ JSON',
	'STYLES_DEMO_JSON_URL'				=> 'Liên kết JSON',
	'STYLES_DEMO_JSON_URL_EXPLAIN'		=> 'Liên kết của tập tin dữ liệu JSON.',
	'STYLES_DEMO_LANG_ENABLE'			=> 'Cho phép chuyển ngôn ngữ',
	'STYLES_DEMO_LANG_SWITCH'			=> 'Ngôn ngữ chuyển',
	'STYLES_DEMO_LANG_SWITCH_EXPLAIN'	=> 'Ngôn ngữ chuyển và mặc định phải khác nhau.',
	'STYLES_DEMO_URL'					=> 'Xem thử giao diện',
	'STYLES_DEMO_SETTINGS_UPDATED'		=> 'Đã cập nhật thiết lập xem thử giao diện.',
));
