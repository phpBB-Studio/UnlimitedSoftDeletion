<?php
/**
 *
 * Unlimited Soft Deletion. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, phpBB Studio, https://www.phpbbstudio.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

/*
 * Some characters you may want to copy&paste:
 * ’ » “ ” …
 */
$lang = array_merge($lang, array(
	'ACP_UDP_CAT'					=> 'phpBB Studio - Unlimited Soft Deletion',

	'ACP_FORUM_SOFTDELETE_ALL'		=> 'User Soft Delete Posts',
	'ACP_FORUM_SOFTDELETE_ALL_DESC'	=> 'Whether or not we want users to ever be able to soft delete every own post in this forum. This setting overrides the positive UDP permission.',
));
