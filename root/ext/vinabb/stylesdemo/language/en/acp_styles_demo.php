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
	'ACP_STYLES_DEMO_EXPLAIN'	=> 'Thanks you for testing “Styles Demo”. Check for stable versions as soon at <a href="https://github.com/VinaBB/Styles.Demo">GitHub</a>.',

	'DEFAULT_LANGUAGE'	=> 'Default language',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'No extra languages to select.',

	'SELECT_LANGUAGE'					=> 'Select a language',
	'STYLES_DEMO_ACP_ENABLE'			=> 'Enable demo for ACP styles',
	'STYLES_DEMO_ACP_ENABLE_EXPLAIN'	=> 'When enabled, guests will be assigned administrator permissions to access the fake ACP. However, no changes saved in this demo mode.',
	'STYLES_DEMO_JSON_ENABLE'			=> 'Get style data via JSON',
	'STYLES_DEMO_JSON_URL'				=> 'JSON URL',
	'STYLES_DEMO_JSON_URL_EXPLAIN'		=> 'Enter the URL of JSON data file.',
	'STYLES_DEMO_LANG_ENABLE'			=> 'Enable language switcher',
	'STYLES_DEMO_LANG_SWITCH'			=> 'Switch language',
	'STYLES_DEMO_LANG_SWITCH_EXPLAIN'	=> 'The switch and default language must be different.',
	'STYLES_DEMO_URL'					=> 'Styles Demo',
	'STYLES_DEMO_SETTINGS_UPDATED'		=> 'The style demo settings have been updated.',
));
