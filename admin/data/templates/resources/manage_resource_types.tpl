<form method="post">
	

	<div class="panel panel-default">
		 <div class="panel-heading">Modify Existing Types (Check Box to Delete)</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<label>Resource Type Name</label>
				</div>
				<div class="col-md-6">
					<label>Icon URL</label>
				</div>	
			</div>
		  	{foreach from=$type_list item=r}
		  		<div class="row">
					<div class="col-md-6">
						<div class="input-group">
				  			<span class="input-group-addon">
				  				<input type="checkbox" name="delete[{$r->get_id()}]" value="{$r->get_id()}" />
				  				
				  			</span>
				  			<input type="text" name="update[{$r->get_id()}]" class="form-control" value="{$r->get_name()}">
				  		</div>
					</div>
					<div class="col-md-6">
						<input type="text" name="update_icon[{$r->get_id()}]" class="form-control" value="{$r->get_icon_path()}">
					</div>	
		  		</div>	
		  		
		  	{/foreach}
		 </div>
	</div>
		
	<div class="panel panel-default">
		<div class="panel-heading">Add New Types</div>
		<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<label>Resource Type Name</label>
					</div>
					<div class="col-md-6">
						<label>Icon URL</label>
					</div>	
				</div>
			{for $i=0 to 4}
				<div class="row">
					<div class="col-md-6">
						<input type="text" name="new[{$i}]" class="form-control">
					</div>
					<div class="col-md-6">
						<input type="text" name="new_icon[{$i}]" class="form-control">
					</div>	
				</div>
				
			{/for}
		</div>
	</div>

	<input type="hidden" name="page" value="{$page}"/>
	<input type="hidden" name="xsrf" value="{$xsrf}"/>
	<button type="submit">Save Changes</button>
</form>