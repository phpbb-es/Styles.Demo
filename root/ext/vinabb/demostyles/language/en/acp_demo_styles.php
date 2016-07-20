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
	'ACP_DEMO_STYLES_EXPLAIN'	=> 'Thanks you for testing “Demo Styles”. Check for stable versions as soon at <a href="https://github.com/VinaBB/Demo.Styles">GitHub</a>.',

	'DEFAULT_LANGUAGE'					=> 'Default language',
	'DEMOSTYLES_ACP_ENABLE'				=> 'Enable demo for ACP styles',
	'DEMOSTYLES_JSON_ENABLE'			=> 'Fetch more style data via JSON',
	'DEMOSTYLES_JSON_URL'				=> 'JSON URL',
	'DEMOSTYLES_JSON_URL_EXPLAIN'		=> 'Enter the URL of JSON data file.',
	'DEMOSTYLES_LANG_ENABLE'			=> 'Enable language switcher',
	'DEMOSTYLES_LANG_SWITCH'			=> 'Switch language',
	'DEMOSTYLES_LANG_SWITCH_EXPLAIN'	=> 'The switch and default language must be different.',
	'DEMOSTYLES_URL'					=> 'Demo styles',
	'DEMOSTYLES_SETTINGS_UPDATED'		=> 'Demo styles settings have been updated.',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'No extra languages to select.',

	'SELECT_LANGUAGE'	=> 'Select a language',
));
