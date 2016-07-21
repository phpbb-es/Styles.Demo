<?php
/**
* This file is part of the VinaBB Demo Styles package.
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
	'ACP_DEMO_STYLES_EXPLAIN'	=> 'Cảm ơn bạn đã dùng thử “Demo Styles”. Phiên bản ổn định hơn đang được phát triển tại <a href="https://github.com/VinaBB/Demo.Styles">GitHub</a>.',

	'DEFAULT_LANGUAGE'					=> 'Ngôn ngữ mặc định',
	'DEMOSTYLES_ACP_ENABLE'				=> 'Cho phép xem thử giao diện quản trị',
	'DEMOSTYLES_ACP_ENABLE_EXPLAIN'		=> 'Nếu bật, khách sẽ được cấp quyền quản trị để truy cập vào bảng quản trị giả. Tuy nhiên, không có thay đổi nào được lưu trong chế độ giả lập này.',
	'DEMOSTYLES_JSON_ENABLE'			=> 'Lấy dữ liệu giao diện từ JSON',
	'DEMOSTYLES_JSON_URL'				=> 'Liên kết JSON',
	'DEMOSTYLES_JSON_URL_EXPLAIN'		=> 'Liên kết của tập tin dữ liệu JSON.',
	'DEMOSTYLES_LANG_ENABLE'			=> 'Cho phép chuyển ngôn ngữ',
	'DEMOSTYLES_LANG_SWITCH'			=> 'Ngôn ngữ chuyển',
	'DEMOSTYLES_LANG_SWITCH_EXPLAIN'	=> 'Ngôn ngữ chuyển và mặc định phải khác nhau.',
	'DEMOSTYLES_URL'					=> 'Xem thử giao diện',
	'DEMOSTYLES_SETTINGS_UPDATED'		=> 'Đã cập nhật thiết lập xem thử giao diện.',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'Không có thêm ngôn ngữ khác để chọn.',

	'SELECT_LANGUAGE'	=> 'Chọn ngôn ngữ',
));
