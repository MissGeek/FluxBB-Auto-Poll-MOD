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


if (empty($cur_topic))
{
	if ($pun_user['is_guest'] || $pun_config['o_show_dot'] == '0')
		$sql = str_replace('moved_to', 'moved_to, question', $sql);
	else
		$sql = str_replace('t.moved_to', 't.moved_to, t.question', $sql);
}
else
{
	if (file_exists(PUN_ROOT.'lang/'.$pun_user['language'].'/poll.php'))
		require PUN_ROOT.'lang/'.$pun_user['language'].'/poll.php';
	else
		require PUN_ROOT.'lang/English/poll.php';	

	if ($cur_topic['question'] != '')
	{
		if ($pun_config['o_censoring'] == '1')
			$cur_topic['question'] = censor_words($cur_topic['question']);

		$subject = $lang_poll['Poll'].': '.$subject;
	}
}

?>
