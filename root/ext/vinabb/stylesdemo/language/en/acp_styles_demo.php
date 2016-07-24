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

	'ERROR_JSON_URL_NOT_RESPONSE'	=> 'The JSON URL does not response.',
	'ERROR_JSON_URL_NOT_VALID'		=> 'The JSON URL is not valid.',
	'ERROR_PHANTOM_NOT_EXEC'		=> 'The file “%s” must be executable.',
	'ERROR_PHANTOM_NOT_FOUND'		=> 'The directory “%s” does not exist.',
	'ERROR_PHANTOM_NOT_WRITE'		=> 'The directory “%s” must be writable.',

	'GET_PHANTOM_BSD'		=> 'Open Terminal and type <code>sudo pkg install phantomjs</code> to get binary packages. Copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_LINUX'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-linux-x86_64.tar.bz2</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_LINUX_32'	=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-linux-i686.tar.bz2</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_MAC'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-macosx.zip </samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_NO_OS'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and get the appropriate download package for your server OS. Extracted it and copy the directory “bin” to the following path: %2$s. You must grant the executable permission to the directory “bin”.',
	'GET_PHANTOM_WIN'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-windows.zip</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must grant the permission “Read &amp; execute” and “Write” to the file “bin/phantomjs.exe”.',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'No extra languages to select.',

	'OS_NAME_USING'	=> 'Your server is running on %s.',

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

	'SCREENSHOT_RESOLUTION'				=> 'Screen resolution',
	'SCREENSHOT_RESOLUTION_EXPLAIN'		=> 'Set the screen resolution for PhantomJS.',
	'SCREENSHOT_TYPE'					=> 'Screenshot type',
	'SCREENSHOT_TYPE_EXPLAIN'			=> 'PhantomJS allows you to take automatically style screenshots. It is free software/open source, and is available for Windows, Mac, Linux, FreeBSD.',
	'SCREENSHOT_TYPE_JSON'				=> 'Get link from JSON',
	'SCREENSHOT_TYPE_LOCAL'				=> 'Local images',
	'SCREENSHOT_TYPE_PHANTOM'			=> 'Use PhantomJS to create',
	'SELECT_RESOLUTION'					=> 'Select a resolution',
	'SELECT_LANGUAGE'					=> 'Select a language',
	'STYLES_DEMO_ACP_ENABLE'			=> 'Enable demo for ACP styles',
	'STYLES_DEMO_ACP_ENABLE_EXPLAIN'	=> 'When enabled, guests will be assigned administrator permissions to access the fake ACP. However, no changes saved in this demo mode.',
	'STYLES_DEMO_AUTO_TOGGLE'			=> 'Close style bar when switch to another style',
	'STYLES_DEMO_JSON_ENABLE'			=> 'Style data source',
	'STYLES_DEMO_JSON_ENABLE_NO'		=> 'Local <var>style.cfg</var>',
	'STYLES_DEMO_JSON_ENABLE_NO_DESC'	=> 'You can define in this file more variables:<br>
	<code>
		<br>style_author = {designer name}
		<br>style_presets = {number of presets}
		<br>style_responsive = {1 if supported responsive, 0 if not}
		<br>style_price = {price of the style, 0 if free}
		<br>style_price_label = {price with currency symbol, empty if free}
		<br>style_details = {link_to_source_info_page}
		<br>style_download = {direct_link_to_file if free download or purchase_link if paid}
	</code>',
	'STYLES_DEMO_JSON_ENABLE_YES'		=> 'Remote JSON file',
	'STYLES_DEMO_JSON_URL'				=> 'JSON URL',
	'STYLES_DEMO_JSON_URL_EXPLAIN'		=> 'Enter the URL of JSON data file.',
	'STYLES_DEMO_LANG_ENABLE'			=> 'Enable language switcher',
	'STYLES_DEMO_LANG_SWITCH'			=> 'Switch language',
	'STYLES_DEMO_LANG_SWITCH_EXPLAIN'	=> 'The switch and default language must be different.',
	'STYLES_DEMO_LOGO_TEXT'				=> 'Hover logo text',
	'STYLES_DEMO_PHONE_WIDTH'			=> 'Phone mode width',
	'STYLES_DEMO_TABLET_WIDTH'			=> 'Tablet mode width',
	'STYLES_DEMO_URL'					=> 'Styles Demo',
	'STYLES_DEMO_SETTINGS_UPDATED'		=> 'The style demo settings have been updated.',
));
