<form method="post" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="url" class="col-sm-2 control-label">Select Resource List</label>
		<div class="col-sm-10">
			<select class="form-control" name="resource_list" id="resource_list" required onChange="this.form.submit();">
				<option value=""></option>
				{foreach from=$all_resource_lists item=rl}
					<option value="{$rl->get_id()}" {if isset($r)&&$r->get_id()==$rl->get_id()}selected{/if}>{$rl->get_name()}</option>
				{/foreach}
			</select>
			
			<input type="hidden" name="page" value="{$page}"/>
			<input type="hidden" name="xsrf" value="{$xsrf}"/>
		</div>
	</div>

</form>
{if isset($r)}
	
	<form method="post" class="form-horizontal" role="form">
		
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">List Name (Internal Use Only)</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="name" name="name" maxlength="255" value="{$r->get_name()}" required>
			</div>
		</div>
		<div class="form-group">
			<label for="display_name" class="col-sm-2 control-label">Display Title</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="display_name" name="display_name" maxlength="255" value="{$r->get_display_name()}">
			</div>
		</div>
		<div class="form-group">
			<label for="icon" class="col-sm-2 control-label">Icon URL</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="icon" name="icon" maxlength="255" value="{$r->get_icon_path()}">
			</div>
		</div>
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Header Text</label>
			<div class="col-sm-10">
				<textarea class="form-control" id="name" name="header" rows="5">{$r->get_heading()}</textarea>
				<span class="help-block">Description text will be publicly accessible.  Newline characters will be converted to HTML, but do not use any additional HTML formatting.
			</div>
		</div>
		
		{if count($collections)>0}
			<div class="form-group">
				<label for="collections" class="col-sm-2 control-label">Include in collections</label>
				<div class="col-sm-10">
					{foreach from=$collections item=rl}
						<div class="checkbox">
							<input type="checkbox" name="collection[{$rl->get_id()}]" value="{$rl->get_id()}" {if array_key_exists($rl->get_id(), $collection_memberships)}checked{/if}> 
							{$rl->get_name()} <input type="number" name="collection_sort[{$rl->get_id()}]" placeholder="Sort Order" {if array_key_exists($rl->get_id(), $collection_memberships)}value="{$collection_memberships[$rl->get_id()]['sort']}" {/if}size=3/>
						</div>
					{/foreach}
				</div>
			</div>
		{/if}
		
		{if count($resources)>0}
			<div class="form-group">
				<label for="resource" class="col-sm-2 control-label">Resources to Include</label>
				<div class="col-sm-10">
					{foreach from=$resources item=rl}
						<div class="checkbox">
							<input type="checkbox" name="resource[{$rl->get_id()}]" value="{$rl->get_id()}" {if $res->resource_is_in_list($r, $rl->get_id())}checked{/if}> 
							{$rl->get_title()} <input type="number" name="resource_sort[{$rl->get_id()}]" placeholder="Sort Order" {if $res->resource_is_in_list($r, $rl->get_id())}value="{$r->pick_resource_item($rl->get_id())->get_sort_order()}"{/if} size=3/>
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
	  	<input type="hidden" name="resource_list" value="{$r->get_id()}"/>
	  	<input type="hidden" name="process" value="1"/>
		<input type="hidden" name="page" value="{$page}"/>
		<input type="hidden" name="xsrf" value="{$xsrf}"/>
	</form>
{/if}