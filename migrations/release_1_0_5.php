<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\migrations;

use phpbb\db\migration\migration;

/**
* Migration: release_1_0_5
* To do: Remove unnecessary config items in the migration 'release_1_0_0'
*/
class release_1_0_5 extends migration
{
	/**
	* List of required migrations
	*
	* @return array
	*/
	static public function depends_on()
	{
		return array('\vinabb\stylesdemo\migrations\release_1_0_0');
	}

	/**
	* Update data
	*
	* @return array
	*/
	public function update_data()
	{
		return array(
			array('config.remove', array('vinabb_stylesdemo_lang_enable')),
			array('config.remove', array('vinabb_stylesdemo_json_enable'))
		);
	}
}
