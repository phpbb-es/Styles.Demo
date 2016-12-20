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
	'ACP_MANAGE_ACP_STYLE_DATA'	=> 'Quản lý giao diện quản trị',

	'LOG_ACP_STYLE_ADD'			=> '<strong>Đã thêm giao diện quản trị mới</strong><br />» %s',
	'LOG_ACP_STYLE_DATA_EDIT'	=> '<strong>Đã sửa dữ liệu giao diện quản trị</strong><br>» %s',
	'LOG_ACP_STYLE_DELETE'		=> '<strong>Đã gỡ bỏ giao diện quản trị</strong><br />» %s'
]);
