<?php
/***********************************************************************

  Caleb Champlin (med_mediator@hotmail.com)

  This file is is a modification of a file from of PunBB.

************************************************************************/

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';


if ($pun_user['g_read_board'] == '0')
	message($lang_common['No view'], false, '403 Forbidden');


$pollid = isset($_POST['poll_id']) ? intval($_POST['poll_id']) : 0;
if ($pollid < 1)
	message($lang_common['Bad request'], false, '404 Not found');

// Fetch some info about the poll
$result = $db->query('SELECT f.id, f.forum_name, f.moderators, f.redirect_url, fp.post_replies, fp.post_topics, t.subject, t.closed, poll.ptype, poll.options, poll.voters, poll.votes FROM '.$db->prefix.'polls AS poll RIGHT JOIN '.$db->prefix.'topics AS t ON poll.pollid=t.id INNER JOIN '.$db->prefix.'forums AS f ON f.id=t.forum_id LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND t.id='.$pollid) or error('Unable to fetch topic and poll info', __FILE__, __LINE__, $db->error());

if (!$db->num_rows($result))
	message($lang_common['Bad request'], false, '404 Not found');

$cur_poll = $db->fetch_assoc($result);

// Sort out who the moderators are and if we are currently a moderator (or an admin)
$mods_array = ($cur_poll['moderators'] != '') ? unserialize($cur_poll['moderators']) : array();
$is_admmod = ($pun_user['g_id'] == PUN_ADMIN || ($pun_user['g_id'] == PUN_MOD && array_key_exists($pun_user['username'], $mods_array))) ? true : false;

// Do we have permission to vote?
if ((((($cur_poll['post_replies'] == '' && $pun_user['g_post_replies'] == '0') || $cur_poll['post_replies'] == '0')) ||
	(isset($cur_poll['closed']) && $cur_poll['closed'] == '1')) &&
	!$is_admmod)
	message($lang_common['No permission'], false, '403 Forbidden');


// Letting guests vote is silly and undermines the whole purpose of a poll
if ($pun_user['is_guest'])
	message($lang_common['No permission'], false, '403 Forbidden');

// Load the poll.php language file
if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/poll.php'))
	require PUN_ROOT.'lang/'.$pun_user['language'].'/poll.php';
else
	require PUN_ROOT.'lang/English/poll.php';	

if (isset($_POST['form_sent']))
{
	
	// Make sure form_user is correct
	if ($pun_user['is_guest'] || $_POST['form_user'] != $pun_user['username'])
		message($lang_common['Bad request'], false, '404 Not found');


	// Grab the options for the poll
	$options = unserialize($cur_poll['options']);
    
	// If there have already been voters grab them and their respective voters
	if (!empty($cur_poll['voters']))
		$voters = unserialize($cur_poll['voters']);
	else
		$voters = array();

	if (!empty($cur_poll['votes']))
		$votes = unserialize($cur_poll['votes']);
	else
		$votes = array();


	// Get the poll type
	$ptype = $cur_poll['ptype'];

	// Check if the person has already voted
	if (in_array($pun_user['id'], $voters))
	message($lang_poll['Already voted']);

	// Did they submit a null vote?
	if (empty($_POST['null']))
	{

		// Based on the poll type increate the value
			if ($ptype == 1)
		{
			$myvote = intval(trim($_POST['vote']));
			if ((empty($myvote)) || (!array_key_exists($myvote, $options)))			
				message($lang_common['Bad request'], false, '404 Not found');

			// Don't ask blame phps silly error checking ;)
			if (isset($votes[$myvote]))
				$votes[$myvote]++;
			else
				$votes[$myvote] = 1;
		}
		else if ($ptype == 2) 
		{
			while (list($key, $value) = each($_POST['options'])) 
			{
				if (!empty($value) && array_key_exists($key, $options)) 
				{
					if (isset($votes[$key]))
						$votes[$key]++;
					else
						$votes[$key] = 1;
				} 
			}
		}
		else
			message($lang_common['Bad request'], false, '404 Not found');
	}
	// Add the voter to the voters array
	$voters[] = $pun_user['id'];
	// Serialize the array
	$voters_serialized = serialize($voters);
	// Same with the votes
	$votes_serialized = serialize($votes);

	// Update the database.
	$db->query('UPDATE '.$db->prefix.'polls SET votes=\''.$votes_serialized.'\', voters=\''.$voters_serialized.'\' WHERE pollid='.$pollid) or error('Unable to update poll', __FILE__, __LINE__, $db->error());

	redirect('viewtopic.php?id='.$pollid, $lang_poll['Vote success']);
}
else
	message($lang_common['Bad request'], false, '404 Not found');
