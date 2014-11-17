<html>
	<head>
		<title>MOOC-Ed Video Transcript</title>
		<!-- NC State Bootstrap CSS -->
		<link href="https://cdn.ncsu.edu/brand-assets/bootstrap/css/bootstrap.css"
		rel="stylesheet" media="screen" type="text/css" />
		
		<!-- jQuery 2.1.0 -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
		
		<!-- Bootstrap JS -->
		<script src="https://cdn.ncsu.edu/brand-assets/bootstrap/js/bootstrap.min.js">
		</script>
		
	</head>
	<body>
		<div class="page-header">
			<div class="row">
				<div class="col-md-4 text-center">
					<a href="http://www.mooc-ed.org"><img src="img/logo-mooc.png" alt="MOOC-Ed"></a>
				</div>
				<div class="col-md-4 text-center">
					<a href="http://www.fi.ncsu.edu"><img src="img/logo-fi.png" alt="Friday Institute for Educational Innovation"></a>
				</div>
				<div class="col-md-4 text-center">
					<a href="http://ced.ncsu.edu"><img src="img/logo-ced.png" alt="NC State College of Education"></a>
				</div>
			</div>
		</div>
		
	<div class="text-center">
	<h3>MOOC-Ed Video Transcript{if strlen($res->get_title())>0}: {$res->get_title()}{/if}</h3>
	<h4><a href="https://www.youtube.com/watch?v={$res->get_video_id()}" target="_blank">https://www.youtube.com/watch?v={$res->get_video_id()}</a></h4>
</div>
	<div class="panel panel-default">
	  <div class="panel-body">
	    	{str_replace("\n","<br>",$res->get_transcript())}
	  </div>
	</div>
	</body>
</html>