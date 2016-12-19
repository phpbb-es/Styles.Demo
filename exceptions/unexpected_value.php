<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\exceptions;

class unexpected_value extends base
{
	/**
	* Translate this exception
	*
	* @param \phpbb\user $user
	* @return string
	*/
	public function get_message(\phpbb\user $user)
	{
		return $this->translate_portions($user, $this->message_full, 'EXCEPTION_UNEXPECTED_VALUE');
	}
}
