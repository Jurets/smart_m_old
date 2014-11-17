<?php
	header( 'Content-Type: text/javascript' );
	require_once("../config.php");
?>

// This is the first thing we add ------------------------------------------
$(document).ready(function() {
    
    $('.rate_widget').each(function(i) {
        var widget = this;
        var out_data = {
            widget_id : $(widget).attr('id'),
            lesson: window.lesson,
            user: window.user,
            hashed_user: window.hashed_user,
            course: window.course,
            function: 'rate',
            fetch: 1
        };
        $.post(
            '<?php echo ABS_URL; ?>/jquery_processor.php',
            out_data,
            function(INFO) {
                $(widget).data( 'fsr', INFO );
                set_votes(widget);
            },
            'json'
        );
    });


    $('.ratings_stars').hover(
        // Handles the mouseover
        function() {
            $(this).prevAll().andSelf().addClass('ratings_over');
            $(this).nextAll().removeClass('ratings_vote'); 
        },
        // Handles the mouseout
        function() {
            $(this).prevAll().andSelf().removeClass('ratings_over');
            // can't use 'this' because it wont contain the updated data
            set_votes($(this).parent());
        }
    );
    
    
    // This actually records the vote
    $('.ratings_stars').bind('click', function() {
        var star = this;
        var widget = $(this).parent();
        
        var clicked_data = {
            clicked_on : $(star).attr('class'),
            widget_id : $(star).parent().attr('id'),
            lesson: window.lesson,
            user: window.user,
            hashed_user: window.hashed_user,
            function: 'rate',
            course: window.course,
        };
        $.post(
            '<?php echo ABS_URL; ?>/jquery_processor.php',
            clicked_data,
            function(INFO) {
                widget.data( 'fsr', INFO );
                set_votes(widget);
            },
            'json'
        ); 
    });
    
    
    
});

function set_votes(widget) {
	var rating = $(widget).data('fsr').rating;
	var average = $(widget).data('fsr').average;
     
    $(widget).find('.star_' + rating).prevAll().andSelf().addClass('ratings_vote');
    $(widget).find('.star_' + rating).nextAll().removeClass('ratings_vote'); 
    if(average!=-1)$(widget).find('.total_votes').text('Average Rating: '+ average );
}
// END FIRST THING



function get_resource(id){
	document.getElementById('get_resource').elements['resource_id'].value=id;
    document.getElementById('get_resource').submit();
}

	
      var tag = document.createElement('script');

      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

function chapter_marker(index, timestamp){
		players[index].seekTo(timestamp, true);
		players[index].playVideo();
		var clicked_data = {
            timestamp: timestamp,
            lesson: window.lesson,
            user: window.user,
            hashed_user: window.hashed_user,
            function: 'video_chapter',
            course: window.course,
            video: index
        };
        $.post(
            '<?php echo ABS_URL?>/jquery_processor.php',
            clicked_data
        ); 

	}

function onStateChange(event){

		var clicked_data = {
            timestamp: event.target.getCurrentTime(),
            event: event.data,
            lesson: window.lesson,
            user: window.user,
            hashed_user: window.hashed_user,
            function: 'video_seek_event',
            course: window.course,
            video: event.target.getIframe().id
        };
        $.post(
            '<?php echo ABS_URL?>/jquery_processor.php',
            clicked_data
        ); 
	}
	
function get_transcript(video){
	document.getElementById('get_resource').action = '<?php echo ABS_URL; ?>/index.php';
	document.getElementById('get_resource').elements['video_transcript_id'].value = video;
	document.getElementById('get_resource').submit();
}


