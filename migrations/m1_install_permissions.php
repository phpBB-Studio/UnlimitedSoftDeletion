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

class m1_install_permissions extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v32x\v325');
	}

	/**
	 * Update data stored in the database during extension installation.
	 *
	 * @return	array	Array of data update instructions
	 * @access public
	 */
	public function update_data()
	{
		return array(
			array('permission.add', array('u_udp')),
		);
	}

}
