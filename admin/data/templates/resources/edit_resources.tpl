<form method="post" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="url" class="col-sm-2 control-label">Select Resource Name: </label>
		<div class="col-sm-10">
			<select class="form-control" name="resource" id="resource" required onChange="this.form.submit();">
				<option value=""></option>
				{foreach from=$resources item=rl}
					<option value="{$rl->get_id()}" {if isset($r)&&$r->get_id()==$rl->get_id()}selected{/if}>{$rl->get_title()} ({$rl->get_url()})</option>
				{/foreach}
			</select>
			
			<input type="hidden" name="page" value="{$page}"/>
			<input type="hidden" name="xsrf" value="{$xsrf}"/>
		</div>
	</div>

</form>
{if isset($r)}
<form method="post" class="form-horizontal" role="form">
				<span class="help-block">Note: All fields are publicly accessible.</span>
	<div class="form-group">
		<label class="col-sm-2 control-label">ID</label>
		<div class="col-sm-10">
			<input type="text" value="{$r->get_id()}" class="form-control" disabled/>
		</div>
	</div>
	<div class="form-group">
		<label for="url" class="col-sm-2 control-label">Type</label>
		<div class="col-sm-10">
			<select class="form-control" name="type" id="type" required>
				<option value=""></option>
				{foreach from=$resource_types item=rt}
					<option value="{$rt->get_id()}" {if $r->get_type()->get_id()==$rt->get_id()}selected{/if}>{$rt->get_name()}</option>
				{/foreach}
			</select>
		</div>
	</div>
	
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Resource Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="name" maxlength="255" value="{$r->get_title()}" required>
		</div>
	</div>

	<div class="form-group">
		<label for="url" class="col-sm-2 control-label">URL</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="url" name="url" maxlength="512" value="{$r->get_url()}" required>
		</div>
	</div>
	<div class="form-group">
		<label for="author" class="col-sm-2 control-label">Author</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="author" name="author"  value="{$r->get_author()}" maxlength="128">
		</div>
	</div>
	<div class="form-group">
		<label for="organization" class="col-sm-2 control-label">Source</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="organization" name="organization"  value="{$r->get_organization()}" maxlength="128">
		</div>
	</div>
	<div class="form-group">
		<label for="copyright" class="col-sm-2 control-label">Copyright Info</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="copyright" name="copyright"  value="{$r->get_copyright()}" maxlength="64">
		</div>
	</div>
	<div class="form-group">
		<label for="time" class="col-sm-2 control-label">Time Estimate</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="time" name="time" maxlength="64"  value="{$r->get_time_estimate()}" placeholder="Enter estimated time to read this resource IN WHOLE MINUTES.">
		</div>
	</div>
	<div class="form-group">
		<label for="tags" class="col-sm-2 control-label">Tags</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="tags" name="tags" value="{implode($r->get_tags(),",")}" placeholder="Comma-separated list of tags.  No spaces allowed.">
		</div>
	</div>
	<div class="form-group">
		<label for="embed" class="col-sm-2 control-label">Embed Code</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="embed" name="embed" rows="5">{$r->get_embed_code()}</textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Description</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="description" name="description" rows="5" required>{$r->get_description()}</textarea>
			<span class="help-block">Newline characters will be converted to HTML, but do not use any additional HTML formatting.</span>
		</div>
	</div>
	
	{if count($resource_lists)>0}
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Select Lists to Include</label>
			<div class="col-sm-10">
				{foreach from=$resource_lists item=rl}
					<div class="checkbox">
						<input type="checkbox" name="list[{$rl->get_id()}]"  {if array_key_exists($rl->get_id(),$list_memberships)}checked{/if} value="{$rl->get_id()}">
						{$rl->get_name()} <input type="number" name="sort[{$rl->get_id()}]" {if array_key_exists($rl->get_id(),$list_memberships)}value="{$list_memberships[$rl->get_id()]['sort']}"{/if} placeholder="Sort Order"/>
					</div>
				{/foreach}
			</div>
		</div>
	{/if}
	
 <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Save Changes</button>
    </div>
  </div>
  	<input type="hidden" name="process" value="1"/>
  	<input type="hidden" name="resource" value="{$r->get_id()}"/>
	<input type="hidden" name="page" value="{$page}"/>
	<input type="hidden" name="xsrf" value="{$xsrf}"/>
</form>
{/if}