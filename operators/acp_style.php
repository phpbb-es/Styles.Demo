<?php
/**
* This file is part of the VinaBB Styles Demo package.
*
* @copyright (c) VinaBB <vinabb.vn>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace vinabb\stylesdemo\operators;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Operator for a set of ACP styles
*/
class acp_style implements acp_style_interface
{
	/** @var ContainerInterface $container */
	protected $container;

	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	/** @var string $table_name */
	protected $table_name;

	/**
	* Constructor
	*
	* @param ContainerInterface						$container	Container object
	* @param \phpbb\db\driver\driver_interface		$db			Database object
	* @param string									$table_name	Table name
	*/
	public function __construct(ContainerInterface $container, \phpbb\db\driver\driver_interface $db, $table_name)
	{
		$this->container = $container;
		$this->db = $db;
		$this->table_name = $table_name;
	}

	/**
	* Get number of styles
	*
	* @param bool $only_active True to count only active styles, false to count all
	* @return int
	*/
	public function count_styles($only_active = true)
	{
		$sql_where = $only_active ? 'WHERE style_active = 1' : '';

		$sql = 'SELECT COUNT(style_id) AS counter
			FROM ' . $this->table_name . "
			$sql_where";
		$result = $this->db->sql_query($sql);
		$counter = (int) $this->db->sql_fetchfield('counter');
		$this->db->sql_freeresult($result);

		return $counter;
	}

	/**
	* Get all styles
	*
	* @param bool $only_active True to get only active styles, false to get all
	* @return array
	*/
	public function get_styles($only_active = true)
	{
		$entities = array();
		$sql_where = $only_active ? 'WHERE style_active = 1' : '';

		$sql = 'SELECT *
			FROM ' . $this->table_name . "
			$sql_where";
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$entities[] = $this->container->get('vinabb.stylesdemo.entities.acp_style')->import($row);
		}
		$this->db->sql_freeresult($result);

		return $entities;
	}

	/**
	* Add a style
	*
	* @param \vinabb\stylesdemo\entities\acp_style_interface $entity ACP style entity
	* @return \vinabb\stylesdemo\entities\acp_style_interface
	*/
	public function add_style(\vinabb\stylesdemo\entities\acp_style_interface $entity)
	{
		// Insert the entity to the database
		$entity->insert();

		// Get the newly inserted entity ID
		$id = $entity->get_id();

		// Reload the data to return a fresh entity
		return $entity->load($id);
	}

	/**
	* Delete a style
	*
	* @param int $id Style ID
	* @return bool True if row was deleted, false otherwise
	* @throws \vinabb\stylesdemo\exceptions\out_of_bounds
	*/
	public function delete_style($id)
	{
		$sql = 'DELETE FROM ' . $this->table_name . '
			WHERE style_id = ' . (int) $id;
		$this->db->sql_query($sql);

		// Return true/false if the entity was deleted
		return (bool) $this->db->sql_affectedrows();
	}
}
