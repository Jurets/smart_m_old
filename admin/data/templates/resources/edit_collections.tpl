<form method="post" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="url" class="col-sm-2 control-label">Select Resource List</label>
		<div class="col-sm-10">
			<select class="form-control" name="collection" id="collection" required onChange="this.form.submit();">
				<option value=""></option>
				{foreach from=$collections item=rc}
					<option value="{$rc->get_id()}" {if isset($c)&&$c->get_id()==$rc->get_id()}selected{/if}>{$rc->get_name()}</option>
				{/foreach}
			</select>
			
			<input type="hidden" name="page" value="{$page}"/>
			<input type="hidden" name="xsrf" value="{$xsrf}"/>
		</div>
	</div>

</form>
{if isset($c)}
	

<form method="post" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Collection ID</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" disabled value="{$c->get_id()}" required>
		</div>
	</div>
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Collection Name (Internal Only)</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="name" maxlength="255" value="{$c->get_name()}" required>
		</div>
	</div>
	<div class="form-group">
		<label for="display_name" class="col-sm-2 control-label">Display Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="display_name" name="display_name" maxlength="255" value="{$c->get_display_name()}">
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">Header Text</label>
		<div class="col-sm-10">
			<textarea class="form-control" id="name" name="header" rows="5">{$c->get_heading()}</textarea>
			<span class="help-block">Description text will be publicly accessible.  Newline characters will be converted to HTML, but do not use any additional HTML formatting.
		</div>
	</div>
	{if count($resource_lists)>0}
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Select Lists to Include</label>
			<div class="col-sm-10">
				{foreach from=$resource_lists item=rl}
					<div class="checkbox">
						<input type="checkbox" name="list[{$rl->get_id()}]" value="{$rl->get_id()}" {if $res->list_in_collection($c, $rl->get_id())}checked{/if}>
						{$rl->get_name()} <input type="number" name="sort[{$rl->get_id()}]" {if $res->list_in_collection($c, $rl->get_id())}value="{$c->pick_list($rl->get_id())->get_sort_order()}" {/if} placeholder="Sort Order"/>
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
  	<input type="hidden" name="collection" value="{$c->get_id()}"/>
  	<input type="hidden" name="process" value="1"/>
	<input type="hidden" name="page" value="{$page}"/>
	<input type="hidden" name="xsrf" value="{$xsrf}"/>
</form>
{/if}