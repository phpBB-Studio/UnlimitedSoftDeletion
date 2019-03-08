<?php
/**
 *
 * Unlimited Soft Deletion. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2019, phpBB Studio, https://www.phpbbstudio.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace phpbbstudio\udp\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Main listener.
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			'core.permissions'						=> 'udp_permissions',
			'core.handle_post_delete_conditions'	=> 'udp_handle_post_delete_conditions',
			'core.viewtopic_modify_post_row'		=> 'udp_viewtopic_modify_post_row',
		);
	}

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpBB root path */
	protected $root_path;

	/** @var string PHP file extension */
	protected $php_ext;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\auth\auth						$auth		Auth object
	 * @param  \phpbb\db\driver\driver_interface	$db			Database object
	 * @param  \phpbb\request\request				$request	Request object
	 * @param  \phpbb\user							$user		User object
	 * @param  string								$root_path	phpBB root path
	 * @param  string								$php_ext	PHP file extension
	 * @return void
	 * @access public
	 */
	public function __construct(
		\phpbb\auth\auth $auth,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\request\request $request,
		\phpbb\user $user,
		$root_path,
		$php_ext
	)
	{
		$this->auth			= $auth;
		$this->db			= $db;
		$this->request		= $request;
		$this->user			= $user;
		$this->root_path	= $root_path;
		$this->php_ext		= $php_ext;
	}

	/**
	 * Adds UDP permissions to our category
	 *
	 * @event  core.permissions
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function udp_permissions($event)
	{
		$categories = $event['categories'];
		$permissions = $event['permissions'];

		if (empty($categories['phpbb_studio']))
		{
			$categories['phpbb_studio'] = 'ACL_CAT_PHPBB_STUDIO';

			$event['categories'] = $categories;
		}

		$perms = array('u_udp');

		foreach ($perms as $permission)
		{
			$permissions[$permission] = array('lang' => 'ACL_' . utf8_strtoupper($permission), 'cat' => 'phpbb_studio');
		}

		$event['permissions'] = $permissions;
	}

	/**
	 * Allows the user to softdelete the post (all permissions and conditions are ignored)
	 *
	 * @event  core.handle_post_delete_conditions
	 * @param  \phpbb\event\object	$event		The event object
	 * @return void
	 * @access public
	 */
	public function udp_handle_post_delete_conditions($event)
	{
		/* Check the forum first, that's fast */
		if (!$this->udp_forum_enabled($event['forum_id']))
		{
			return;
		}

		/* Now check the permission */
		if (!$this->auth->acl_get('u_udp'))
		{
			return;
		}

		$event['force_softdelete_allowed'] = true;
	}

	/**
	 * Show the [X] button in the post row also to authed users
	 *
	 * @event  core.viewtopic_modify_post_row
	 * @param  \phpbb\event\object	$event		The event object
	 * @return void
	 * @access public
	 */
	public function udp_viewtopic_modify_post_row($event)
	{
		/* Check the forum first, that's fast */
		$forum_id = $this->request->variable('f', 0);

		if (!$this->udp_forum_enabled((int) $forum_id))
		{
			return;
		}

		/* Check the topic status, if LOCKED no [X] button will be shown */
		if ($event['topic_data']['topic_status'] == ITEM_LOCKED)
		{
			return;
		}

		/* Now check the permission */
		if (!$this->auth->acl_get('u_udp'))
		{
			return;
		}

		/* Does the post belong to the current user? */
		$poster_id = (int) $event['poster_id'];

		if ($this->user->data['user_id'] != $poster_id)
		{
			return;
		}

		$post_id = (int) $event['row']['post_id'];

		$softdelete_allowed = true;

		$udp_post_link = append_sid("{$this->root_path}posting.$this->php_ext", 'mode=' . (($softdelete_allowed) ? 'soft_delete' : 'delete') . "&amp;f=$forum_id&amp;p=$post_id");

		$event->update_subarray('post_row', 'U_DELETE', $udp_post_link);
	}

	/**
	 * Check if the UDP extension is enabled for a specific forum.
	 *
	 * @param  int		$forum_id		The forum identifier
	 * @return bool						Whether or not the extension is enabled for this forum
	 * @access protected
	 */
	protected function udp_forum_enabled($forum_id)
	{
		if (empty($forum_id))
		{
			return false;
		}

		$sql = 'SELECT udp_f_softdelete_all
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . (int) $forum_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$s_enabled = (bool) $this->db->sql_fetchfield('udp_f_softdelete_all');
		$this->db->sql_freeresult($result);

		return $s_enabled;
	}

}
