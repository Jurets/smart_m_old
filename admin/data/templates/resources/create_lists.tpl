<form method="post" class="form-horizontal" role="form">
	
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">List Name (Internal Use Only)</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="name" maxlength="255" required>
		</div>
	</div>
	<div class="form-group">
		<label for="display_name" class="col-sm-2 control-label">List Heading Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="display_name" name="display_name" maxlength="255">
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Header Text</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="name" name="header" rows="5"></textarea>
			<span class="help-block">Description text will be publicly accessible.  Newline characters will be converted to HTML, but do not use any additional HTML formatting.
		</div>
	</div>
	<div class="form-group">
		<label for="icon" class="col-sm-2 control-label">Icon URL</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="icon" name="icon" maxlength="255">
		</div>
	</div>
	{if count($collections_list)>0}
		<div class="form-group">
			<label for="collections" class="col-sm-2 control-label">Include in collections</label>
			<div class="col-sm-10">
				{foreach from=$collections_list item=rl}
					<div class="checkbox">
						<input type="checkbox" name="collection[{$rl->get_id()}]" value="{$rl->get_id()}"> 
						{$rl->get_name()} <input type="number" name="collection_sort[{$rl->get_id()}]" placeholder="Sort Order" size=3/>
					</div>
				{/foreach}
			</div>
		</div>
	{/if}
	
	{if count($resource_list)>0}
		<div class="form-group">
			<label for="resource" class="col-sm-2 control-label">Resources to Include</label>
			<div class="col-sm-10">
				{foreach from=$resource_list item=rl}
					<div class="checkbox">
						<input type="checkbox" name="resource[{$rl->get_id()}]" value="{$rl->get_id()}"> 
						{$rl->get_title()} <input type="number" name="resource_sort[{$rl->get_id()}]" placeholder="Sort Order" size=3/>
					</div>
				{/foreach}
			</div>
		</div>
	{/if}
	
 <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Create List</button>
    </div>
  </div>
  	<input type="hidden" name="process" value="1"/>
	<input type="hidden" name="page" value="{$page}"/>
	<input type="hidden" name="xsrf" value="{$xsrf}"/>
</form>