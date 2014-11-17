{include file='header.inc.tpl'}
{literal}
<script type="text/javascript">
	  var tag = document.createElement('script');

      tag.src = "//www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
	  if(typeof players === 'undefined'){
		  var players = new Array();
		  var elements = new Array();
	}
	function logVideoEvent(video, event){

		var clicked_data = {
            event: event,
            lesson: window.lesson,
            user: window.user,
            hashed_user: window.hashed_user,
            function: 'log_video_event',
            course: window.course,
            video: video
        };
        $.post(
            '{/literal}{$smarty.const.ABS_URL}{literal}/jquery_processor.php',
            clicked_data
        ); 

	}
</script>
{/literal}
<div id="player_{$video->get_id()}" class="player">
	<div style="text-align: center;">
	<div id="v_{$video->get_id()}"></div>
	</div>
	{if strlen($video->get_podcast_url())>0||strlen($video->get_transcript())>0 }
		<div class="video_metadata">
			[
			{if strlen($video->get_podcast_url())>0}
				<a href="{$video->get_podcast_url()}" target="_blank" onClick="logVideoEvent('v_{$video->get_id()}', '400');">Podcast</a>	
			{/if}
			{if strlen($video->get_podcast_url())>0 && strlen($video->get_transcript())>0}
			 | 
			{/if}
			{if strlen($video->get_transcript())>0}
				<a href="javascript: void(0);" onClick="get_transcript('{$video->get_id()}');">Transcript</a>	
			{/if}
			]
		</div>
	{/if}
	{literal}
		 <script type="text/javascript">  
			 elements.push({'div':'v_{/literal}{$video->get_id()}{literal}', 'video':'{/literal}{$video->get_video_id()}{literal}'});
			 
		</script>
	{/literal}
	
	{if count($video->get_chapters())>0}
		<div class="video_chapter_section">
			<div class="video_chapter_header">In this video (Click to Advance):</div>
			{foreach $video->get_chapters() as $time=>$title}
				<div class="video_chapter"><a onClick="chapter_marker('v_{$video->get_id()}', '{$time}')">{math equation="floor(x/60)" x=$time}min, {math equation="x%60" x=$time}sec: {$title}</a></div>
			{/foreach}
		</div>
	{/if}
	<div class="resource_description">
		{str_replace("\n", "<br/>", $video->get_description())}
	</div>
	<div class="resource_ratings resource_rating_video">
		<div class="resource_rating_header">How useful was this resource?</div>
		<div id="rating_video_{$video->get_id()}" class="rate_widget">
	        <div class="star_1 ratings_stars"></div>
	        <div class="star_2 ratings_stars"></div>
	        <div class="star_3 ratings_stars"></div>
	        <div class="star_4 ratings_stars"></div>
	        <div class="star_5 ratings_stars"></div>
	        <div class="total_votes"></div>
	    </div>
	</div>
</div>

<script type="text/javascript">
function onYouTubePlayerAPIReady() {

	    $(document).ready(function() { 
	    
	        window.elements.forEach(function(e){   
			  logVideoEvent(e.div, 100);
	             
	          players[e.div] = (new YT.Player(e.div, {
	              width: '640',
	              videoId: e.video,
	              playerVars:{
	              	'rel': 0
	              },
	              events:{
	              'onStateChange': 'onStateChange',
	              }
	            })
	          )
	      })
	    });
	}
	
window.onbeforeunload = function() {
	window.elements.forEach(function(e){   
		logVideoEvent(e.div, 200);
	});
};

</script>