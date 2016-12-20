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
	'ACP_ADD_ACP_STYLE'				=> 'Thêm giao diện quản trị mới',
	'ACP_ADD_ACP_STYLE_EXPLAIN'		=> 'Công cụ này chỉ thêm giao diện quản trị vào trang xem thử, nó không cài đặt bất cứ thứ gì vào hệ thống vì phpBB không hỗ trợ chức năng này.',
	'ACP_ADD_ACP_STYLE_UNAVAILABLE'	=> 'Vui lòng chép các giao diện quản trị mới vào thư mục <samp>%s</samp>.',
	'ACP_MANAGE_STYLE_DATA_EXPLAIN'	=> 'Nguồn dữ liệu cho trang xem thử giao diện nếu không dùng tập tin JSON. Mỗi lần bạn cập nhật phiên bản mới cho các giao diện, bạn cũng cần phải cập nhật lại giá trị phiên bản giao diện và phiên bản phpBB từ những tập tin .cfg mới.',
	'ACP_STYLES_DEMO_EXPLAIN'		=> 'Cảm ơn bạn đã sử dụng “Styles Demo”. Kiểm tra các phiên bản mới tại <a href="https://github.com/VinaBB/Styles.Demo/releases">GitHub</a>.',

	'CFG_UPDATE_PHPBB_VERSION'			=> 'Cập nhật phiên bản phpBB từ <var>style.cfg</var>',
	'CFG_UPDATE_PHPBB_VERSION_CONFIRM'	=> 'Bạn chắc chắn muốn cập nhật phiên bản phpBB từ tập tin .cfg?',
	'CFG_UPDATE_PHPBB_VERSION_EXPLAIN'	=> 'Chỉ cập nhật nếu phiên bản phpBB từ tập tin này mới hơn phiên bản đã lưu trong cơ sở dữ liệu.',
	'CFG_UPDATE_PHPBB_VERSION_SUCCESS'	=> [
		0	=> 'Không có phiên bản phpBB nào mới hơn từ tập tin .cfg.',
		1	=> 'Đã cập nhật %d giao diện.',
		2	=> 'Đã cập nhật %d giao diện.'
	],
	'CFG_UPDATE_VERSION'				=> 'Cập nhật phiên bản giao diện từ <var>style.cfg</var>',
	'CFG_UPDATE_VERSION_CONFIRM'		=> 'Bạn chắc chắn muốn cập nhật phiên bản giao diện từ tập tin .cfg?',
	'CFG_UPDATE_VERSION_EXPLAIN'		=> 'Chỉ cập nhật nếu phiên bản giao diện từ tập tin này mới hơn phiên bản đã lưu trong cơ sở dữ liệu.',
	'CFG_UPDATE_VERSION_SUCCESS'		=> [
		0	=> 'Không có phiên bản giao diện nào mới hơn từ tập tin .cfg.',
		1	=> 'Đã cập nhật %d giao diện.',
		2	=> 'Đã cập nhật %d giao diện.'
	],
	'CONFIRM_DELETE_STYLE_DATA'			=> 'Bạn chắc chắn muốn gỡ bỏ giao diện này?',

	'DEFAULT_LANGUAGE'	=> 'Ngôn ngữ mặc định',
	'DESIGNER_URL'		=> 'Trang chủ người thiết kế',

	'EDIT_STYLE_DATA'				=> 'Sửa dữ liệu giao diện',
	'ERROR_DISABLE_DEFAULT_STYLE'	=> 'Không thể tắt giao diện mặc định.',
	'ERROR_DUPLICATE_STYLE'			=> 'Tên giao diện bị trùng:<br>%s',
	'ERROR_DUPLICATE_STYLE_NAME'	=> 'Tên: %s',
	'ERROR_DUPLICATE_STYLE_PATH'	=> 'Đường dẫn: %s',
	'ERROR_JSON_URL_NOT_RESPONSE'	=> 'Liên kết JSON không phản hồi.',
	'ERROR_JSON_URL_NOT_VALID'		=> 'Liên kết JSON không hợp lệ.',
	'ERROR_PHANTOM_NOT_EXEC'		=> 'Tập tin “%s” phải được cấp phép thực thi.',
	'ERROR_PHANTOM_NOT_FOUND'		=> 'Thư mục “%s” không tồn tại.',
	'ERROR_PHANTOM_NOT_WRITE'		=> 'Thư mục “%s” phải được cấp phép ghi.',
	'ERROR_STYLE_NAME_EMPTY'		=> 'Phải nhập tên giao diện.',
	'ERROR_STYLE_NAME_EXIST'		=> 'Tên giao diện “%s” bị trùng.',

	'GET_PHANTOM_BSD'		=> 'Mở Terminal và gõ <code>sudo pkg install phantomjs</code> để tải về các gói phần mềm của PhantomJS. Chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_LINUX'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-linux-x86_64.tar.bz2</samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_LINUX_32'	=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-linux-i686.tar.bz2</samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_MAC'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-macosx.zip </samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải CHMOD 755 cho thư mục “bin”.',
	'GET_PHANTOM_NO_OS'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và chọn gói tải về tương ứng cho hệ điều hành máy chủ. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải cấp quyền thực thi cho thư mục “bin”.',
	'GET_PHANTOM_WIN'		=> 'Truy cập trang <a href="%1$s">tải về PhantomJS</a> và tải về tập tin <samp>phantomjs-2.x.x-windows.zip</samp>. Giải nén và chép thư mục “bin” vào đường dẫn sau: %2$s. Bạn phải cấp quyền “Đọc &amp; thực thi” cho tập tin “bin/phantomjs.exe”.',

	'LINK_DETAILS'			=> 'Trang thông tin',
	'LINK_DETAILS_EXPLAIN'	=> 'Trang thông tin chi tiết hay liên kết gốc.',
	'LINK_DOWNLOAD'			=> 'Liên kết tải về',
	'LINK_DOWNLOAD_EXPLAIN'	=> 'Trang tải về hoặc liên kết trực tiếp của tập tin nếu giao diện miễn phí, ngược lại là trang mua sản phẩm.',
	'LINK_MIRROR'			=> 'Liên kết dự phòng',
	'LINK_MIRROR_ADD'		=> 'Thêm mới',
	'LINK_MIRROR_EXPLAIN'	=> 'Liên kết dự phòng chỉ dành cho giao diện tải về miễn phí.',
	'LINK_MIRROR_NAME'		=> 'Tên',
	'LINK_MIRROR_URL'		=> 'Liên kết',
	'LINK_SUPPORT'			=> 'Liên kết hỗ trợ',
	'LINK_SUPPORT_DEFAULT'	=> 'Liên kết hỗ trợ chung',
	'LINK_SUPPORT_EXPLAIN'	=> 'Để trống để dùng liên kết hỗ trợ chung.',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'Không có thêm ngôn ngữ khác để chọn.',
	'NO_STYLE'					=> 'Giao diện không tồn tại.',
	'NO_STYLE_ID'				=> 'Giao diện không xác định.',

	'OS_NAME_USING'	=> 'Hệ điều hành máy chủ của bạn là %s.',

	'PRICE_LABEL'			=> 'Giá kèm tiền tệ',
	'PRICE_LABEL_EXPLAIN'	=> 'Để trống nếu miễn phí.',

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
	'SCREENSHOT_TYPE_EXPLAIN'			=> 'PhantomJS giúp bạn tạo hình giao diện thu nhỏ hoàn toàn tự động. Đây là phần mềm miễn phí, mã nguồn mở và hỗ trợ các hệ điều hành: Windows, macOS, Linux, FreeBSD.',
	'SCREENSHOT_TYPE_JSON'				=> 'Lấy liên kết hình từ JSON',
	'SCREENSHOT_TYPE_LOCAL'				=> 'Hình có sẵn',
	'SCREENSHOT_TYPE_PHANTOM'			=> 'Dùng PhantomJS để tạo',
	'SELECT_RESOLUTION'					=> 'Chọn độ phân giải',
	'SELECT_LANGUAGE'					=> 'Chọn ngôn ngữ',
	'STYLE_DATA_ADDED'					=> 'Đã thêm giao diện quản trị.',
	'STYLE_DATA_DELETED'				=> 'Đã gỡ bỏ giao diện quản trị.',
	'STYLE_DATA_UPDATED' 				=> 'Đã sửa dữ liệu giao diện.',
	'STYLES_DEMO_ACP_ENABLE'			=> 'Cho phép xem thử giao diện quản trị',
	'STYLES_DEMO_ACP_ENABLE_EXPLAIN'	=> 'Nếu bật, khách sẽ được cấp quyền quản trị để truy cập vào bảng quản trị giả. Tuy nhiên, không có thay đổi nào được lưu trong chế độ giả lập này.',
	'STYLES_DEMO_AUTO_TOGGLE'			=> 'Đóng thanh giao diện khi chuyển sang giao diện khác',
	'STYLES_DEMO_DOWNLOAD_DIRECT'		=> 'Chế độ tải về',
	'STYLES_DEMO_DOWNLOAD_DIRECT_NO'	=> 'Mở trang mới',
	'STYLES_DEMO_DOWNLOAD_DIRECT_YES'	=> 'Tải trực tiếp',
	'STYLES_DEMO_JSON_ENABLE'			=> 'Nguồn dữ liệu giao diện',
	'STYLES_DEMO_JSON_ENABLE_NO'		=> 'Cơ sở dữ liệu',
	'STYLES_DEMO_JSON_ENABLE_YES'		=> 'Từ tập tin JSON',
	'STYLES_DEMO_JSON_URL'				=> 'Liên kết JSON',
	'STYLES_DEMO_JSON_URL_EXPLAIN'		=> 'Liên kết của tập tin dữ liệu JSON.',
	'STYLES_DEMO_LANG_SWITCH'			=> 'Ngôn ngữ chuyển',
	'STYLES_DEMO_LANG_SWITCH_EXPLAIN'	=> 'Ngôn ngữ chuyển và mặc định phải khác nhau.',
	'STYLES_DEMO_LOGO_TEXT'				=> 'Giới thiệu',
	'STYLES_DEMO_PHONE_WIDTH'			=> 'Chiều rộng chế độ điện thoại',
	'STYLES_DEMO_TABLET_WIDTH'			=> 'Chiều rộng chế độ máy tính bảng',
	'STYLES_DEMO_URL'					=> 'Xem thử giao diện',
	'STYLES_DEMO_SETTINGS_UPDATED'		=> 'Đã cập nhật thiết lập xem thử giao diện.',

	'UPDATE_TOOLS'	=> 'Công cụ cập nhật'
]);
