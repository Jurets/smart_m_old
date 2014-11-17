<script>
	var course = '{$course->get_id()}';
	var user = '{$user}';
	var hashed_user = '{$hashed_user}';
	var lesson = '{$lesson}';
	

</script>


<script language="JavaScript" src="{$smarty.const.ABS_URL}/script.js.php" />
<link rel="stylesheet" href="{$smarty.const.ABS_URL}/style.css.php"/>


<form id="get_resource" method="post" action="{$smarty.const.ABS_URL}/get_resource.php" target="_blank">
	<input type="hidden" name="course" value='{$course->get_id()}'/>
	<input type="hidden" name="user" value='{$user}'/>
	<input type="hidden" name="hashed_user" value='{$hashed_user}'/>
	<input type="hidden" name="lesson" value='{$lesson}'/>
	<input type="hidden" name="resource_id"/>
	<input type="hidden" name="video_transcript_id"/>
	
</form>