<form method="post" class="form-horizontal" role="form">

	<div class="panel panel-default">
	  	<div class="panel-body">
	    	<div class="form-group">
				<label for="video_id" class="col-sm-2 control-label">YouTube Video ID</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" id="video_id" name="video_id" maxlength="64" required>
				</div>
			</div>
		</div>
	</div>		
	
	<div class="panel panel-default">
		  <div class="panel-heading">
		    	<h3 class="panel-title">Internal Reference Information</h3>
		  </div>
		  <div class="panel-body">
		  		<div class="form-group">
					<label for="internal_title" class="col-sm-2 control-label">Video Name</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="internal_title" name="internal_title" maxlength="255" required>
					</div>
				</div>
		  </div>
		  <div class="form-group">
		<label for="internal_notes" class="col-sm-2 control-label">Notes</label>
			<div class="col-sm-10">
				<textarea class="form-control" id="internal_notes" name="internal_notes" rows="5" ></textarea>
				<span class="help-block">Do not use any additional HTML formatting.</span>
			</div>
		</div>
	</div>		
	
	<div class="panel panel-default">
		  <div class="panel-heading">
		    	<h3 class="panel-title">Publicly Accessible Fields</h3>
		  </div>
		  <div class="panel-body">
		  		<div class="form-group">
					<label for="title" class="col-sm-2 control-label">Video Title</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="title" name="title" maxlength="255">
					</div>
				</div>
		  </div>
		  <div class="panel-body">
		  		<div class="form-group">
					<label for="podcast_url" class="col-sm-2 control-label">Podcast URL</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="podcast_url" name="podcast_url" maxlength="128">
					</div>
				</div>
		  </div>
		  <div class="form-group">
			<label for="description" class="col-sm-2 control-label">Description/Followup Text</label>
				<div class="col-sm-10">
					<textarea class="form-control" id="description" name="description" rows="8" ></textarea>
					<span class="help-block">HTML allowed.</span>
				</div>
			</div>
		 <div class="form-group">
			<label for="transcript" class="col-sm-2 control-label">Transcript</label>
				<div class="col-sm-10">
					<textarea class="form-control" id="transcript" name="transcript" rows="8" ></textarea>
					<span class="help-block">No HTML.  Newlines will be converted.</span>
				</div>
			</div>
	</div>		
	
	<div class="panel panel-default">
	  <div class="panel-heading">
	    	<h3 class="panel-title">Chapter Markers</h3>
	  </div>
	  <div class="panel-body">
	  		{for $i=0 to 15}
		    	<div class="form-group">
					<div class="col-sm-2">
						<input type="number" class="form-control" id="minutes[{$i}]" name="minutes[{$i}]" maxlength="3" placeholder="MM">
					</div>
					<div class="col-sm-2">
						<input type="number" class="form-control" id="seconds[{$i}]" name="seconds[{$i}]" maxlength="3" placeholder="SS">
					</div>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="chapter_titles[{$i}]" name="chapter_titles[{$i}]" maxlength="100" placeholder="Chapter Title">
					</div>
				</div>
			{/for}
	  </div>
	</div>
		
 <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Create Resource</button>
    </div>
  </div>
  	<input type="hidden" name="process" value="1"/>
	<input type="hidden" name="page" value="{$page}"/>
	<input type="hidden" name="xsrf" value="{$xsrf}"/>
</form>