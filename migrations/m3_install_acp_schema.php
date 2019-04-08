<?php
/**
 *
 * Unlimited Soft Deletion. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, phpBB Studio, https://www.phpbbstudio.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbstudio\udp\migrations;

class m3_install_acp_schema extends \phpbb\db\migration\migration
{
	/**
	 * Check if the migration is effectively installed (entirely optional).
	 *
	 * @return bool 		True if this migration is installed, False if this migration is not installed
	 * @access public
	 */
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'forums', 'udp_f_softdelete_all');
	}

	/**
	 * Assign migration file dependencies for this migration.
	 *
	 * @return array		Array of migration files
	 * @access public
	 * @static
	 */
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v32x\v325');
	}

	/**
	 * Add the UDP extension forums schema to the database.
	 *
	 * @return array 		Array of table schema
	 * @access public
	 */
	public function update_schema()
	{
		return array(
			'add_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'udp_f_softdelete_all'	=> array('BOOL', 1),
				),
			),
		);
	}

	/**
	 * Drop the UDP extension forums schema from the database.
	 *
	 * @return array		Array of table schema
	 * @access public
	 */
	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'forums'	=> array(
					'udp_f_softdelete_all',
				),
			),
		);
	}

}
