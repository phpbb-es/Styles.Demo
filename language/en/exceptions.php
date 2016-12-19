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
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

/**
* All language files should use UTF-8 as their encoding
* and the files must not contain a BOM.
*/

/**
* These are errors which can be triggered by sending invalid data to the extension API.
*
* These errors will never show to a user unless they are either
* modifying the extension code OR unless they are writing an extension
* which makes calls to this extension.
*
* Translators: Do not need to translate these language strings ;)
*/
$lang = array_merge($lang, array(
	'EXCEPTION_DUPLICATE'			=> 'This unique field is duplicate.',
	'EXCEPTION_EMPTY'				=> 'This required field is missing.',
	'EXCEPTION_INVALID'				=> 'This field contains invalid characters.',
	'EXCEPTION_INVALID_ARGUMENT'	=> 'Invalid argument specified for “%1$s”. Reason: %2$s',
	'EXCEPTION_INVALID_EMAIL'		=> 'This field is not a valid email.',
	'EXCEPTION_INVALID_URL'			=> 'This field is not a valid URL.',
	'EXCEPTION_NOT_AUTH'			=> 'The user are not authorized to perform this action. Reason: “%s”.',
	'EXCEPTION_NOT_EXISTS'			=> 'This field does not exist.',
	'EXCEPTION_OUT_OF_BOUNDS'		=> 'The field “%s” received data beyond its bounds.',
	'EXCEPTION_TOO_LONG'			=> 'This field is longer than the maximum length.',
	'EXCEPTION_UNEXPECTED_VALUE'	=> 'The field “%1$s” received unexpected data. Reason: %2$s'
));
