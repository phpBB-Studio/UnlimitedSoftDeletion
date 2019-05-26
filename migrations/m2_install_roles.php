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

class m2_install_roles extends \phpbb\db\migration\migration
{
	/**
	 * Assign migration file dependencies for this migration.
	 *
	 * @return array		Array of migration files
	 * @access public
	 * @static
	 */
	public static function depends_on()
	{
		return array(
			'\phpbb\db\migration\data\v32x\v325',
			'\phpbbstudio\udp\migrations\m1_install_permissions'
		);
	}

	/**
	 * Update or delete data stored in the database during extension installation.
	 *
	 * @return array Array of data update instructions.
	 * @access public
	 */
	public function update_data()
	{
		$data = array();

		/* User permissions */
		if ($this->role_exists('ROLE_USER_STANDARD'))
		{
			$data[] = array('permission.permission_set', array('ROLE_USER_STANDARD', 'u_udp'));
		}

		return $data;
	}

	/**
	 * Checks whether the given role does exist or not.
	 *
	 * @param  String	$role	The name of the role
	 * @return bool				True if the role exists, false otherwise.
	 * @access private
	 */
	private function role_exists($role)
	{
		$sql = 'SELECT role_id
		FROM ' . ACL_ROLES_TABLE . "
		WHERE role_name = '" . $this->db->sql_escape($role) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$role_id = $this->db->sql_fetchfield('role_id');
		$this->db->sql_freeresult($result);

		return $role_id;
	}
}
