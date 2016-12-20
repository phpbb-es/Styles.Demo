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
	'ACP_MANAGE_ACP_STYLE_DATA'	=> 'Manage ACP styles',

	'LOG_ACP_STYLE_ADD'			=> '<strong>Added new ACP style</strong><br />» %s',
	'LOG_ACP_STYLE_DATA_EDIT'	=> '<strong>Edited ACP style data</strong><br>» %s',
	'LOG_ACP_STYLE_DELETE'		=> '<strong>Removed ACP style</strong><br />» %s'
));
