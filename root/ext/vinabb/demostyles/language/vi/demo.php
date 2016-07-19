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
	'CLOSE'		=> 'Đóng',
	'COPYRIGHT'	=> 'Bản quyền',

	'DEMO_STYLES'			=> 'Xem thử giao diện',
	'DEMO_STYLES_EXPLAIN'	=> 'The Holy Land of phpBB',
	'DESIGNER'				=> 'Người thiết kế',
	'DESKTOP'				=> 'Máy tính',
	'DETAILS'				=> 'Chi tiết',
	'DOWNLOAD'				=> 'Tải về',

	'FREE'	=> 'Miễn phí',

	'LANG_SWITCH'	=> '%1$s » %2$s',

	'MODE_ACP'		=> 'Chuyển sang giao diện quản trị',
	'MODE_FRONTEND'	=> 'Chuyển sang giao diện chính',

	'NO_ACP_STYLES'	=> 'Không có giao diện quản trị nào để xem thử.',

	'PHONE'			=> 'Điện thoại',
	'PHPBB_BADGE'	=> 'phpBB %s',
	'PHPBB_VERSION'	=> 'Phiên bản phpBB',
	'PRESETS'		=> 'Số bản màu',
	'PRICE'			=> 'Giá',
	'PURCHASE'		=> 'Mua',

	'REPONSIVE'	=> 'Reponsive',

	'SELECT_STYLE'			=> 'Chọn giao diện…',
	'SELECT_STYLE_EXPLAIN'	=> 'Chọn giao diện xem thử',
	'SIGN_CURRENCY'			=> '$',

	'TABLET'	=> 'Máy tính bảng',

	'VERSION'	=> 'Phiên bản',
));
