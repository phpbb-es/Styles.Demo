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

	'GET_PHANTOM_BSD'		=> 'Open Terminal and type <code>sudo pkg install phantomjs</code> to get binary packages. Copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_LINUX'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-linux-x86_64.tar.bz2</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_LINUX_32'	=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-linux-i686.tar.bz2</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_MAC'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-macosx.zip </samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_NO_OS'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and get the appropriate download package for your server OS. Extracted it and copy the directory “bin” to the following path: %2$s. You must grant the executable permission to the directory “bin”.',
	'GET_PHANTOM_WIN'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-windows.zip</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must grant the permission “Read &amp; Execute” to the directory “bin”.',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'Không có thêm ngôn ngữ khác để chọn.',

	'OS_NAME_USING'	=> 'Hệ điều hành máy chủ của bạn là %s.',

	'SELECT_LANGUAGE'					=> 'Chọn ngôn ngữ',
	'STYLES_DEMO_ACP_ENABLE'			=> 'Cho phép xem thử giao diện quản trị',
	'STYLES_DEMO_ACP_ENABLE_EXPLAIN'	=> 'Nếu bật, khách sẽ được cấp quyền quản trị để truy cập vào bảng quản trị giả. Tuy nhiên, không có thay đổi nào được lưu trong chế độ giả lập này.',
	'STYLES_DEMO_JSON_ENABLE'			=> 'Nguồn dữ liệu giao diện',
	'STYLES_DEMO_JSON_ENABLE_NO'		=> 'Use <var>style.cfg</var>',
	'STYLES_DEMO_JSON_ENABLE_NO_DESC'	=> 'You can define in this file more variables:<br>
	<code>
		<br>style_author = {designer name}
		<br>style_presets = {number of presets}
		<br>style_responsive = {1 if supported responsive, 0 if not}
		<br>style_price = {price of the style, 0 if free}
		<br>style_price_label = {price with currency symbol, empty if free}
		<br>style_details = {link_to_source_info_page}
		<br>style_download = {direct_link_to_file if free download or purchase_link if paid}
	</code>',
	'STYLES_DEMO_JSON_ENABLE_YES'		=> 'Remote JSON file',
	'STYLES_DEMO_JSON_URL'				=> 'Liên kết JSON',
	'STYLES_DEMO_JSON_URL_EXPLAIN'		=> 'Liên kết của tập tin dữ liệu JSON.',
	'STYLES_DEMO_LANG_ENABLE'			=> 'Cho phép chuyển ngôn ngữ',
	'STYLES_DEMO_LANG_SWITCH'			=> 'Ngôn ngữ chuyển',
	'STYLES_DEMO_LANG_SWITCH_EXPLAIN'	=> 'Ngôn ngữ chuyển và mặc định phải khác nhau.',
	'STYLES_DEMO_LOGO_TEXT'				=> 'Giới thiệu',
	'STYLES_DEMO_URL'					=> 'Xem thử giao diện',
	'STYLES_DEMO_SETTINGS_UPDATED'		=> 'Đã cập nhật thiết lập xem thử giao diện.',
));
