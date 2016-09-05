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
	'ACP_CAT_STYLES_DEMO'	=> 'Styles Demo',
	'ACP_STYLES_DEMO'		=> 'Style demo settings',

	'LOG_STYLES_DEMO_SETTINGS'	=> '<strong>Altered style demo settings</strong>',

	'ROLE_ADMIN_DEMO'		=> 'Demo Admin',
	'ROLE_ADMIN_DEMO_DESC'	=> 'Use only for demo phpBB boards.',

	'UNAVAILABLE_IN_DEMO'	=> 'No changes saved in demo mode.',
));
