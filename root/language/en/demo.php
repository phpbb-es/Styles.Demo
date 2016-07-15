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
	'CLOSE'	=> 'Close',

	'DEMO_STYLES'			=> 'Demo Styles',
	'DEMO_STYLES_EXPLAIN'	=> 'The Holy Land of phpBB',
	'DESKTOP'				=> 'Desktop',
	'DETAILS'				=> 'Details',
	'DOWNLOAD'				=> 'Download',

	'PHONE'			=> 'Phone',
	'PRICE'			=> 'Price',
	'PRICE_DIALOG'	=> 'This style is sale at the price $',
	'PURCHASE'		=> 'Purchase',

	'SELECT_STYLE'			=> 'Select a style',
	'SELECT_STYLE_EXPLAIN'	=> 'Select a style to preview',
	'SWITCH_ACP'				=> 'Switch to ACP Demo',
	'SWITCH_FRONTEND'		=> 'Switch to Front-end Demo',

	'TABLET'	=> 'Tablet',
));
