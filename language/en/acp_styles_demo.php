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
	'ACP_ADD_ACP_STYLE'				=> 'Add new ACP styles',
	'ACP_ADD_ACP_STYLE_EXPLAIN'		=> 'This panel just add ACP styles to the demo page, it installs nothing to your system since phpBB does not support this.',
	'ACP_ADD_ACP_STYLE_UNAVAILABLE'	=> 'Please copy new ACP styles to the directory <samp>%s</samp>.',
	'ACP_MANAGE_STYLE_DATA_EXPLAIN'	=> 'Local data for demo styles if the remote JSON file is not used. Each time you update styles to new versions, you would also like update style version and phpBB version values from updated .cfg files.',
	'ACP_STYLES_DEMO_EXPLAIN'		=> 'Thanks you for using “Styles Demo”. Check for new versions at <a href="https://github.com/VinaBB/Styles.Demo/releases">GitHub</a>.',

	'CFG_UPDATE_PHPBB_VERSION'			=> 'Update phpBB version from <var>style.cfg</var>',
	'CFG_UPDATE_PHPBB_VERSION_CONFIRM'	=> 'Are you sure you want to update phpBB version from .cfg files?',
	'CFG_UPDATE_PHPBB_VERSION_EXPLAIN'	=> 'Only if the phpBB version from this file is newer than which was stored in database.',
	'CFG_UPDATE_PHPBB_VERSION_SUCCESS'	=> array(
		0	=> 'No newer phpBB versions from .cfg files.',
		1	=> '%d style has been updated.',
		2	=> '%d styles have been updated.'
	),
	'CFG_UPDATE_VERSION'				=> 'Update style version from <var>style.cfg</var>',
	'CFG_UPDATE_VERSION_CONFIRM'		=> 'Are you sure you want to update style version from .cfg files?',
	'CFG_UPDATE_VERSION_EXPLAIN'		=> 'Only if the style version from this file is newer than which was stored in database.',
	'CFG_UPDATE_VERSION_SUCCESS'		=> array(
		0	=> 'No newer style versions from .cfg files.',
		1	=> '%d style has been updated.',
		2	=> '%d styles have been updated.'
	),
	'CONFIRM_DELETE_STYLE_DATA'			=> 'Are you sure you want to remove this style?',

	'DEFAULT_LANGUAGE'	=> 'Default language',
	'DESIGNER_URL'		=> 'Designer homepage',

	'EDIT_STYLE_DATA'				=> 'Edit style data',
	'ERROR_DISABLE_DEFAULT_STYLE'	=> 'You could not disable the default style.',
	'ERROR_DUPLICATE_STYLE'			=> 'Detected duplicate style names:<br>%s',
	'ERROR_DUPLICATE_STYLE_NAME'	=> 'Name: %s',
	'ERROR_DUPLICATE_STYLE_PATH'	=> 'Path: %s',
	'ERROR_JSON_URL_NOT_RESPONSE'	=> 'The JSON URL does not response.',
	'ERROR_JSON_URL_NOT_VALID'		=> 'The JSON URL is not valid.',
	'ERROR_PHANTOM_NOT_EXEC'		=> 'The file “%s” must be executable.',
	'ERROR_PHANTOM_NOT_FOUND'		=> 'The directory “%s” does not exist.',
	'ERROR_PHANTOM_NOT_WRITE'		=> 'The directory “%s” must be writable.',
	'ERROR_STYLE_NAME_EMPTY'		=> 'You must enter a style name.',
	'ERROR_STYLE_NAME_EXIST'		=> 'The style name “%s” already exists.',

	'GET_PHANTOM_BSD'		=> 'Open Terminal and type <code>sudo pkg install phantomjs</code> to get binary packages. Copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_LINUX'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-linux-x86_64.tar.bz2</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_LINUX_32'	=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-linux-i686.tar.bz2</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_MAC'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-macosx.zip </samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must CHMOD 755 for the directory “bin”.',
	'GET_PHANTOM_NO_OS'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and get the appropriate download package for your server OS. Extracted it and copy the directory “bin” to the following path: %2$s. You must grant the executable permission to the directory “bin”.',
	'GET_PHANTOM_WIN'		=> 'Visit the PhantomJS <a href="%1$s">download page</a> and download the file <samp>phantomjs-2.x.x-windows.zip</samp>. Extracted it and copy the directory “bin” to the following path: %2$s. You must grant the permission “Read &amp; execute” to the file “bin/phantomjs.exe”.',

	'LINK_DETAILS'			=> 'Details link',
	'LINK_DETAILS_EXPLAIN'	=> 'Information page or source link.',
	'LINK_DOWNLOAD'			=> 'Download link',
	'LINK_DOWNLOAD_EXPLAIN'	=> 'Download page or direct link to file if the style is free, otherwise it is purchase page.',
	'LINK_MIRROR'			=> 'Mirror links',
	'LINK_MIRROR_ADD'		=> 'Add new mirror',
	'LINK_MIRROR_EXPLAIN'	=> 'Mirrors are available only for free download styles.',
	'LINK_MIRROR_NAME'		=> 'Name',
	'LINK_MIRROR_URL'		=> 'Mirror URL',
	'LINK_SUPPORT'			=> 'Support link',
	'LINK_SUPPORT_DEFAULT'	=> 'Global support link',
	'LINK_SUPPORT_EXPLAIN'	=> 'Leave this field blank to use the global support link.',

	'NO_EXTRA_LANG_TO_SELECT'	=> 'No extra languages to select.',
	'NO_STYLE'					=> 'The style does not exist.',
	'NO_STYLE_ID'				=> 'No styles specified.',

	'OS_NAME_USING'	=> 'Your server is running on %s.',

	'PRICE_LABEL'			=> 'Price with currency',
	'PRICE_LABEL_EXPLAIN'	=> 'Leave this field blank if free.',

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
	'SCREENSHOT_TYPE_EXPLAIN'			=> 'PhantomJS allows you to take automatically style screenshots. It is free software/open source, and is available for Windows, macOS, Linux, FreeBSD.',
	'SCREENSHOT_TYPE_JSON'				=> 'Get link from JSON',
	'SCREENSHOT_TYPE_LOCAL'				=> 'Local images',
	'SCREENSHOT_TYPE_PHANTOM'			=> 'Use PhantomJS to create',
	'SELECT_RESOLUTION'					=> 'Select a resolution',
	'SELECT_LANGUAGE'					=> 'Select a language',
	'STYLE_DATA_ADDED'					=> 'The ACP styles have been added.',
	'STYLE_DATA_DELETED'				=> 'The ACP styles have been removed.',
	'STYLE_DATA_UPDATED' 				=> 'The style data has been updated.',
	'STYLES_DEMO_ACP_ENABLE'			=> 'Enable demo for ACP styles',
	'STYLES_DEMO_ACP_ENABLE_EXPLAIN'	=> 'When enabled, guests will be assigned administrator permissions to access the fake ACP. However, no changes saved in this demo mode.',
	'STYLES_DEMO_AUTO_TOGGLE'			=> 'Close style bar when switch to another style',
	'STYLES_DEMO_DOWNLOAD_DIRECT'		=> 'Download mode',
	'STYLES_DEMO_DOWNLOAD_DIRECT_NO'	=> 'Open new tab',
	'STYLES_DEMO_DOWNLOAD_DIRECT_YES'	=> 'Direct link',
	'STYLES_DEMO_JSON_ENABLE'			=> 'Style data source',
	'STYLES_DEMO_JSON_ENABLE_NO'		=> 'Database',
	'STYLES_DEMO_JSON_ENABLE_YES'		=> 'Remote JSON file',
	'STYLES_DEMO_JSON_URL'				=> 'JSON URL',
	'STYLES_DEMO_JSON_URL_EXPLAIN'		=> 'Enter the URL of JSON data file.',
	'STYLES_DEMO_LANG_SWITCH'			=> 'Switch language',
	'STYLES_DEMO_LANG_SWITCH_EXPLAIN'	=> 'The switch and default language must be different.',
	'STYLES_DEMO_LOGO_TEXT'				=> 'Hover logo text',
	'STYLES_DEMO_PHONE_WIDTH'			=> 'Phone mode width',
	'STYLES_DEMO_TABLET_WIDTH'			=> 'Tablet mode width',
	'STYLES_DEMO_URL'					=> 'Styles Demo',
	'STYLES_DEMO_SETTINGS_UPDATED'		=> 'The style demo settings have been updated.',

	'UPDATE_TOOLS'	=> 'Update tools'
));
