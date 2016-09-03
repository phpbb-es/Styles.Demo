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
	'ACP_STYLES'			=> array(
		1	=> '%d ACP style',
		2	=> '%d ACP styles',
	),
	'ACP_STYLES_DISABLED'	=> 'The ACP style preview has been disabled.',

	'CLOSE'		=> 'Close',
	'COPYRIGHT'	=> 'Copyright',

	'DESIGNER'	=> 'Designer',
	'DESKTOP'	=> 'Desktop',
	'DETAILS'	=> 'Details',
	'DOWNLOAD'	=> 'Download',

	'FREE'	=> 'Free',

	'LANG_SWITCH'	=> '%1$s » %2$s',

	'MIRROR_LABEL'	=> 'Mirror #%d',
	'MIRRORS'		=> 'Mirrors',
	'MODE_ACP'		=> 'Switch to ACP Demo',
	'MODE_FRONTEND'	=> 'Switch to Front-end Demo',

	'NO_ACP_STYLES'	=> 'No ACP styles available to preview.',

	'PHONE'			=> 'Phone',
	'PHPBB_BADGE'	=> 'phpBB %s',
	'PHPBB_VERSION'	=> 'phpBB version',
	'PRESETS'		=> 'Presets',
	'PRICE'			=> 'Price',
	'PURCHASE'		=> 'Purchase',

	'RESPONSIVE'	=> 'Responsive',

	'SELECT_STYLE'			=> 'Select a style…',
	'SELECT_STYLE_EXPLAIN'	=> 'Select a style to preview',
	'STYLE_NAME'			=> 'Style name',
	'STYLES'				=> array(
		1	=> '%d style',
		2	=> '%d styles',
	),
	'STYLES_DEMO'			=> 'Styles Demo',

	'TABLET'	=> 'Tablet',

	'VERSION'	=> 'Version',
));
