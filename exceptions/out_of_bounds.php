<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\exceptions;

class out_of_bounds extends base
{
	/**
	* Translate this exception
	*
	* @param \phpbb\language\language $language
	* @return string
	*/
	public function get_message(\phpbb\language\language $language)
	{
		return $this->translate_portions($language, $this->message_full, 'EXCEPTION_OUT_OF_BOUNDS');
	}

	/**
	* We do not translate error messages of this exception
	*
	* @param \phpbb\language\language $language
	* @return string
	*/
	public function get_friendly_message(\phpbb\language\language $language)
	{
		return $this->translate_portions($language, $this->message_full, 'EXCEPTION_OUT_OF_BOUNDS');
	}
}
