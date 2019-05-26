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
 * ACP listener.
 */
class acp_listener implements EventSubscriberInterface
{
	/**
	 * Assign functions defined in this class to event listeners in the core.
	 *
	 * @static
	 * @return array
	 * @access public
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_manage_forums_request_data'		=> 'udp_acp_manage_forums_request_data',
			'core.acp_manage_forums_initialise_data'	=> 'udp_acp_manage_forums_initialise_data',
			'core.acp_manage_forums_display_form'		=> 'udp_acp_manage_forums_display_form',
		);
	}

	/** @var \phpbb\request\request */
	protected $request;

	/**
	 * Constructor.
	 *
	 * @param  \phpbb\request\request $request Request object
	 * @return void
	 * @access public
	 */
	public function __construct(\phpbb\request\request $request)
	{
		$this->request = $request;
	}

	/**
	 * (Add/update actions) - Submit form.
	 *
	 * @event  core.acp_manage_forums_request_data
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function udp_acp_manage_forums_request_data($event)
	{
		$event->update_subarray('forum_data', 'udp_f_softdelete_all', $this->request->variable('udp_f_softdelete_all', 0));
	}

	/**
	 * New Forum (Add action) - Default enabled.
	 *
	 * @event  core.acp_manage_forums_initialise_data
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function udp_acp_manage_forums_initialise_data($event)
	{
		if ($event['action'] === 'add')
		{
			/* Add our index to the existing array */
			$event['forum_data'] += ['udp_f_softdelete_all'	=> true];
		}
	}

	/**
	 * ACP forums (template data).
	 *
	 * @event  core.acp_manage_forums_display_form
	 * @param  \phpbb\event\data	$event		The event object
	 * @return void
	 * @access public
	 */
	public function udp_acp_manage_forums_display_form($event)
	{
		$event->update_subarray('template_data', 'S_FORUM_SOFTDELETE_ALL', (bool) $event['forum_data']['udp_f_softdelete_all']);
	}

}
