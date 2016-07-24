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

	'ERROR_JSON_URL_NOT_RESPONSE'	=> 'Liên kết JSON không phản hồi.',
	'ERROR_JSON_URL_NOT_VALID'		=> 'Liên kết JSON không hợp lệ.',
	'ERROR_PHANTOM_NOT_EXEC'		=> 'Tập tin “%s” phải được cấp phép thực thi.',
	'ERROR_PHANTOM_NOT_FOUND'		=> 'Thư mục “%s” không tồn tại.',
	'ERROR_PHANTOM_NOT_WRITE'		=> 'Thư mục “%s” phải được cấp phép ghi.',

	'GET_PHANTOM_BSD'		=> 'Mở Terminal và gõ <code>sudo pkg install phantomjs</code> để tải về các gói phần mềm của PhantomJS. Chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_LINUX'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-linux-x86_64.tar.bz2</samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_LINUX_32'	=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-linux-i686.tar.bz2</samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_MAC'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-macosx.zip </samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_NO_OS'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và chọn gói tải về tương ứng cho hệ điều hành máy chủ. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải cấp quyền thực thi cho thư mục “bin”.',
	'GET_PHANTOM_WIN'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-windows.zip</samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải cấp quyền “Đọc &amp; thực thi” cùng với “Ghi” cho tập tin “bin/phantomjs.exe”.',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'Không có thêm ngôn ngữ khác để chọn.',

	'OS_NAME_USING'	=> 'Hệ điều hành máy chủ của bạn là %s.',

	'RESOLUTION_SVGA'		=> 'SVGA (%1$d x %2$d)',
	'RESOLUTION_XGA'		=> 'XGA (%1$d x %2$d)',
	'RESOLUTION_WXGA'		=> 'WXGA (%1$d x %2$d)',
	'RESOLUTION_HD'			=> 'HD (%1$d x %2$d)',
	'RESOLUTION_WXGA_PLUS'	=> 'WXGA+ (%1$d x %2$d)',
	'RESOLUTION_HD_PLUS'	=> 'HD+ (%1$d x %2$d)',
	'RESOLUTION_FHD'		=> 'Full HD (%1$d x %2$d)',
	'RESOLUTION_QHD'		=> 'QHD (%1$d x %2$d)',
	'RESOLUTION_WQHD'		=> 'WQHD (%1$d x %2$d)',
	'RESOLUTION_UHD'		=> '4K UHD (%1$d x %2$d)',
	'RESOLUTION_5K'			=> '5K UHD (%1$d x %2$d)',
	'RESOLUTION_8K'			=> '8K UHD (%1$d x %2$d)',

	'SCREENSHOT_RESOLUTION'				=> 'Độ phân giải màn hình',
	'SCREENSHOT_RESOLUTION_EXPLAIN'		=> 'Thiết lập độ phân giải màn hình cho PhantomJS.',
	'SCREENSHOT_TYPE'					=> 'Loại hình thu nhỏ',
	'SCREENSHOT_TYPE_EXPLAIN'			=> 'PhantomJS giúp bạn tạo hình giao diện thu nhỏ hoàn toàn tự động. Đây là phần mềm miễn phí, mã nguồn mở và hỗ trợ các hệ điều hành: Windows, Mac, Linux, FreeBSD.',
	'SCREENSHOT_TYPE_JSON'				=> 'Lấy liên kết hình từ JSON',
	'SCREENSHOT_TYPE_LOCAL'				=> 'Hình có sẵn',
	'SCREENSHOT_TYPE_PHANTOM'			=> 'Dùng PhantomJS để tạo',
	'SELECT_RESOLUTION'					=> 'Chọn độ phân giải',
	'SELECT_LANGUAGE'					=> 'Chọn ngôn ngữ',
	'STYLES_DEMO_ACP_ENABLE'			=> 'Cho phép xem thử giao diện quản trị',
	'STYLES_DEMO_ACP_ENABLE_EXPLAIN'	=> 'Nếu bật, khách sẽ được cấp quyền quản trị để truy cập vào bảng quản trị giả. Tuy nhiên, không có thay đổi nào được lưu trong chế độ giả lập này.',
	'STYLES_DEMO_AUTO_TOGGLE'			=> 'Đóng thanh giao diện khi chuyển sang giao diện khác',
	'STYLES_DEMO_JSON_ENABLE'			=> 'Nguồn dữ liệu giao diện',
	'STYLES_DEMO_JSON_ENABLE_NO'		=> 'Từ tập tin <var>style.cfg</var>',
	'STYLES_DEMO_JSON_ENABLE_NO_DESC'	=> 'Bạn có thể gán thêm nhiều biến dữ liệu khác như:<br>
	<code>
		<br>style_author = {tên người thiết kế}
		<br>style_presets = {số lượng bản màu}
		<br>style_responsive = {1 nếu hỗ trợ đa thiết bị, 0 nếu không có}
		<br>style_price = {giá bán giao diện, 0 nếu miễn phí}
		<br>style_price_label = {giá đi kèm đơn vị tiền tệ, để trống nếu miễn phí}
		<br>style_details = {liên_kết_đến_trang_nguồn}
		<br>style_download = {liên_kết_tải_về_trực_tiếp nếu miễn phí hoặc liên_kết_mua nếu có phí}
	</code>',
	'STYLES_DEMO_JSON_ENABLE_YES'		=> 'Từ tập tin JSON',
	'STYLES_DEMO_JSON_URL'				=> 'Liên kết JSON',
	'STYLES_DEMO_JSON_URL_EXPLAIN'		=> 'Liên kết của tập tin dữ liệu JSON.',
	'STYLES_DEMO_LANG_ENABLE'			=> 'Cho phép chuyển ngôn ngữ',
	'STYLES_DEMO_LANG_SWITCH'			=> 'Ngôn ngữ chuyển',
	'STYLES_DEMO_LANG_SWITCH_EXPLAIN'	=> 'Ngôn ngữ chuyển và mặc định phải khác nhau.',
	'STYLES_DEMO_LOGO_TEXT'				=> 'Giới thiệu',
	'STYLES_DEMO_PHONE_WIDTH'			=> 'Chiều rộng chế độ điện thoại',
	'STYLES_DEMO_TABLET_WIDTH'			=> 'Chiều rộng chế độ máy tính bảng',
	'STYLES_DEMO_URL'					=> 'Xem thử giao diện',
	'STYLES_DEMO_SETTINGS_UPDATED'		=> 'Đã cập nhật thiết lập xem thử giao diện.',
));
