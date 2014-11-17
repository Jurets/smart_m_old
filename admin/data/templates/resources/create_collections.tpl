<form method="post" class="form-horizontal" role="form">
	
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Collection Name (Internal Only)</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="name" maxlength="255" required>
		</div>
	</div>
	<div class="form-group">
		<label for="display_name" class="col-sm-2 control-label">Display Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="display_name" name="display_name" maxlength="255" >
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Header Text</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="name" name="header" rows="5"></textarea>
			<span class="help-block">Description text will be publicly accessible.  Newline characters will be converted to HTML, but do not use any additional HTML formatting.
		</div>
	</div>
	{if count($resource_lists)>0}
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Select Lists to Include</label>
			<div class="col-sm-10">
				{foreach from=$resource_lists item=rl}
					<div class="checkbox">
						<input type="checkbox" name="list[{$rl->get_id()}]" value="{$rl->get_id()}">
						{$rl->get_name()} <input type="number" name="sort[{$rl->get_id()}]" placeholder="Sort Order"/>
					</div>
				{/foreach}
			</div>
		</div>
	{/if}
	
 <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Create Collection</button>
    </div>
  </div>
  	<input type="hidden" name="process" value="1"/>
	<input type="hidden" name="page" value="{$page}"/>
	<input type="hidden" name="xsrf" value="{$xsrf}"/>
</form>