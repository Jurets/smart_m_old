<?php

	session_start();
	header( 'Content-Type: text/css' );
	require_once("../config.php");
?>
.icon{
	height: 20px;
}

.collection{
	width: 100%;
	float: left;
}

.collection_heading{
	width: 100%;
	float: left;
	font-size: 12px;
	margin-bottom: 15px;
}

.list{
	width: 100%;
	float: left;
}

.list_title{
	width: 100%;
	float: left;
	padding-bottom: 5px;
}

.list_title h3{
	margin: 0px;
}

.list_heading{
	width: 100%;
	float: left;
	margin-bottom: 15px;
}

.resource{
	width: 100%;

}

.resource_item{
	float: left;
	width: 77%;
	margin-bottom: 15px;
}

.resource_item_title{
	font-size: 13px;
	font-weight: bold;
	display: block;
}

.resource_metadata{
	font-size: 11px;
	display: block;
}

.resource_description{
	display: block;
}

.resource_tags{
	font-size: 11px;
	font-style: italic;
	margin-bottom: 10px;
	display: block;
}


.resource_ratings {
	float: right;
    font-size: 10px;
    margin: 0 auto 40px auto;
    width: 23%
    float: right;
    padding-top: 20px;
}

.resource_rating_video{
	padding-left: 38.5%;
	padding-right: 38.5%;
}

.resource_rating_header{
	text-align: left;
	padding-left: 9px;
}

.player{
	display: block;
}

.video_chapter_section{
	display: block;
	padding-bottom: 10px;
}

.video_chapter_header{
	display: block;
	width: 100%;
	font-weight: bold;
}

.video_chapter{
	width: 100%;
	display: block;
}

.video_chapter a{
	font-color: #c00;
}

.video_metadata{
	text-align: center;
	padding-bottom: 10px;
}

.rate_widget {
    border:     0px;
    overflow:   visible;
    position:   relative;
    width:      159px;
    height:     40px;
    float: right;
    text-align: center;
}
.ratings_stars {
    background: url('<?php echo ABS_URL;?>/img/star_empty.png') no-repeat;
    height:     24px;
    padding-top: 0px;
    padding:    2px;
    width:      25px;
    display: inline-block;
    vertical-align: top;
}
.ratings_vote {
    background: url('<?php echo ABS_URL;?>/img/star_full.png') no-repeat;
}
.ratings_over {
    background: url('<?php echo ABS_URL;?>/img/star_highlight.png') no-repeat;
}
.total_votes {

    top: 25px;
    text-align: center;
    font-size: 10px;
    left: 0;
    padding: 0px;
    position:   absolute;  
    vertical-align: bottom;
    width: 100%;
    backgrond-color: #CCCCCC;
} 


