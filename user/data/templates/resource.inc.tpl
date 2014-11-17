

<div class="resource">
	<div class="resource_item">
		<div class="resource_item_title">
			<img class="icon" src="{$r->get_type()->get_icon_path()}" alt="{$r->get_type()->get_name()}"/>
			<a href="javascript:void(0);" onClick="get_resource({$r->get_id()});" target="_blank">{$r->get_title()}</a>
		</div>
		{if strlen($r->get_author())>0}<div class="resource_metadata"><strong>Author: </strong>{$r->get_author()}</div>{/if}
		{if strlen($r->get_organization())>0}<div class="resource_metadata"><strong>Source: </strong>{$r->get_organization()}</div>{/if}
		{if strlen($r->get_copyright())>0}<div class="resource_metadata"><strong>Copyright Info: </strong>{$r->get_copyright()}</div>{/if}
		<div class="resource_description">
			{$r->get_description()}
		</div>
		{if strlen($r->get_time_estimate())>0}<div class="resource_tags"><strong>Time Estimate:</strong> {$r->get_time_estimate()} minutes</div>{/if}
		{if count($r->get_tags())>0}<div class="resource_tags"><strong>Tags: </strong>{implode(", ",$r->get_tags())}</div>{/if}
		{if strlen($r->get_embed_code())>0}<div class="resource_tags">{$r->get_embed_code()}</div>{/if}
	</div>
	<div class="resource_ratings">
		<div class="resource_rating_header">How useful was this resource?</div>
		<div id="rating_resource_{$r->get_id()}" class="rate_widget">
	        <div class="star_1 ratings_stars"></div>
	        <div class="star_2 ratings_stars"></div>
	        <div class="star_3 ratings_stars"></div>
	        <div class="star_4 ratings_stars"></div>
	        <div class="star_5 ratings_stars"></div>
	        <div class="total_votes"></div>
	    </div>
	</div>
</div>