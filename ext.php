<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo;

class ext extends \phpbb\extension\base
{
	/** @var \phpbb\config\config $config */
	protected $config;

	/** @var \vinabb\stylesdemo\controllers\helper_interface $ext_helper */
	protected $ext_helper;

	/**
	* Constructor
	*/
	public function __construct(ContainerInterface $container, \phpbb\finder $extension_finder, \phpbb\db\migrator $migrator, $extension_name, $extension_path)
	{
		parent::__construct($container, $extension_finder, $migrator, $extension_name, $extension_path);

		$this->config = $this->container->get('config');
		$this->ext_helper = $this->container->get('vinabb.stylesdemo.helper');
	}

	/**
	* Overwrite enable_step to assign the role ROLE_ADMIN_DEMO to the guest user
	* before the extension is enabled.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	public function enable_step($old_state)
	{
		if (isset($this->config['vinabb_stylesdemo_acp_enable']) && $this->config['vinabb_stylesdemo_acp_enable'])
		{
			$this->ext_helper->set_role_admin_demo();
		}

		return parent::enable_step($old_state);
	}

	/**
	* Overwrite disable_step to unassign the role ROLE_ADMIN_DEMO to the guest user
	* before the extension is disabled.
	*
	* @param mixed $old_state State returned by previous call of this method
	* @return mixed Returns false after last step, otherwise temporary state
	*/
	public function disable_step($old_state)
	{
		$this->ext_helper->unset_role_admin_demo();

		return parent::disable_step($old_state);
	}
}
