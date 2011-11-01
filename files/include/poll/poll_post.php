<?php
/**
 * Adapted for FluxBB 1.4 by Ishimaru Chiaki (http://ishimaru-design.servhome.org)
 * Based on work by Caleb Champlin (med_mediator@hotmail.com)
 *
 * Copyright (C) 2008-2011 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */


// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;


if (!isset($checkboxes))
{
	// If it's a new poll
	$add_poll = isset($_POST['add_poll']) ? 1 : 0;
	if ($add_poll)
		redirect('poll_add.php?id='.$new_tid, $lang_post['Post redirect']);
}

else
{
	if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/poll.php'))
		require PUN_ROOT.'lang/'.$pun_user['language'].'/poll.php';
	else
		require PUN_ROOT.'lang/English/poll.php';
		
	// See if user can post polls in this forum
	$result = $db->query('SELECT fp.post_polls FROM '.$db->prefix.'forums AS f LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.post_polls IS NULL OR fp.post_polls=1) AND f.id='.$fid) or error('Unable to fetch forum info', __FILE__, __LINE__, $db->error());

	if ($fid && ($db->num_rows($result)) && $pun_user['g_post_polls'] != '0' && $pun_config['o_poll_enabled'] == '1')
		$checkboxes[] = '<label><input type="checkbox" name="add_poll" value="1" tabindex="'.($cur_index++).'"'.(isset($_POST['add_poll']) ? ' checked="checked"' : '').' />'.$lang_poll['Add poll'].'<br /></label>';
}

?>
