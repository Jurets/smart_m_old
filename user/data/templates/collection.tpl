{include file='header.inc.tpl'}

<div class="collection">
	{if strlen($collection->get_display_name())>0}
		<h3 class="collection_title">
			{$collection->get_display_name()}
		</h3>
	{/if}
	{if strlen($collection->get_heading())>0}
		<div class="collection_heading">
			{$collection->get_heading()|replace:"\n":"<br/>"}
		</div>
	{/if}
	{foreach $collection->get_lists() as $list}
		
		{if strlen($list->get_display_name())>0}
			<div class="list_title">	
				<h3>
					{if strlen($list->get_icon_path())>0}
						<img class="icon" src="{$list->get_icon_path()}" alt="List Icon"/>
					{/if}
					{$list->get_display_name()}
				</h3>
			</div>
		{/if}
		{if strlen($list->get_heading())>0}
			<div class="list_heading">	
				{$list->get_heading()}
			</div>
		{/if}

		{foreach $list->get_items() as $r}
			{include file='resource.inc.tpl'}
		{/foreach}
		
	{/foreach}
</div>