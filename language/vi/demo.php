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
	'ACP_STYLES'			=> [
		1	=> '%d giao diện quản trị',
		2	=> '%d giao diện quản trị'
	],
	'ACP_STYLES_DISABLED'	=> 'Chức năng xem thử giao diện quản trị đã tắt.',

	'CLOSE'		=> 'Đóng',
	'COPYRIGHT'	=> 'Bản quyền',

	'DESIGNER'	=> 'Người thiết kế',
	'DESKTOP'	=> 'Máy tính',
	'DETAILS'	=> 'Chi tiết',
	'DOWNLOAD'	=> 'Tải về',

	'FREE'	=> 'Miễn phí',

	'LANG_SWITCH'	=> '%1$s » %2$s',

	'MIRROR_LABEL'	=> 'Nguồn #%d',
	'MIRRORS'		=> 'Dự phòng',
	'MODE_ACP'		=> 'Chuyển sang giao diện quản trị',
	'MODE_FRONTEND'	=> 'Chuyển sang giao diện chính',

	'NO_ACP_STYLES'	=> 'Không có giao diện quản trị nào để xem thử.',

	'PHONE'			=> 'Điện thoại',
	'PHPBB_BADGE'	=> 'phpBB %s',
	'PHPBB_VERSION'	=> 'Phiên bản phpBB',
	'PRESETS'		=> 'Số bản màu',
	'PRICE'			=> 'Giá',
	'PURCHASE'		=> 'Mua',

	'RESPONSIVE'	=> 'Hỗ trợ đa thiết bị',

	'SELECT_STYLE'			=> 'Chọn giao diện…',
	'SELECT_STYLE_EXPLAIN'	=> 'Chọn giao diện xem thử',
	'STYLE_NAME'			=> 'Tên giao diện',
	'STYLES'				=> [
		1	=> '%d giao diện',
		2	=> '%d giao diện'
	],
	'STYLES_DEMO'			=> 'Xem thử giao diện',
	'SUPPORT'				=> 'Hỗ trợ',

	'TABLET'	=> 'Máy tính bảng',

	'UNKNOWN'	=> 'Không biết',

	'VERSION'	=> 'Phiên bản'
]);
