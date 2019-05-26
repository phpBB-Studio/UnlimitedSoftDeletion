<?php
/**
 *
 * Unlimited Soft Deletion. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, phpBB Studio, https://www.phpbbstudio.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbstudio\udp;

/**
 * Extension base
 */
class ext extends \phpbb\extension\base
{
	/**
	 * Check whether the extension can be enabled.
	 * Provides meaningful(s) error message(s) and the back-link on failure.
	 * CLI compatible
	 *
	 * @return bool
	 */
	public function is_enableable()
	{
		$is_enableable = true;

		$user = $this->container->get('user');
		$user->add_lang_ext('phpbbstudio/udp', 'ext_require');

		$lang = $user->lang;

		if (!(phpbb_version_compare(PHPBB_VERSION, '3.2.5', '>=') && phpbb_version_compare(PHPBB_VERSION, '3.3.0@dev', '<')))
		{
			/**
			 * Despite it seems wrong that's the right approach and not an error in coding
			 * That's done in order to avoid a PHP error like
			 * "Indirect modification of overloaded property phpbb/user::$lang has no effect"
			 * Discussed here: https://www.phpbb.com/community/viewtopic.php?p=14724151#p14724151
			*/
			$lang['EXTENSION_NOT_ENABLEABLE'] .= '<br>' . $user->lang('ERROR_PHPBB_VERSION', '3.2.5', '3.3.0@dev');

			$is_enableable = false;
		}

		$user->lang = $lang;

		return $is_enableable;
	}

}
