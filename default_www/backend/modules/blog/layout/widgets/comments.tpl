<div class="box" id="widgetBlogComments">
	<div class="heading">
		<h3><a href="{$var|geturl:'comments':'blog'}">{$lblLatestComments|ucfirst}</a></h3>
	</div>

	{option:blogNumCommentsToModerate}
	<div class="moderate">
		<div class="oneLiner">
			<p>{$msgCommentsToModerate|sprintf:{$blogNumCommentsToModerate}}</p>
			<div class="buttonHolder">
				<a href="{$var|geturl:'comments':'blog'}#tabModeration" class="button"><span>{$lblModerate|ucfirst}</span></a>
			</div>
		</div>
	</div>
	{/option:blogNumCommentsToModerate}

	{option:blogComments}
	<div class="datagridHolder">
		<table cellspacing="0" class="datagrid">
			<tbody>
				{iteration:blogComments}
				<tr class="{cycle:'odd':'even'}">
					<td><a href="{$blogComments.full_url}">{$blogComments.title}</a></td>
					<td class="name">{$blogComments.author}</td>
				</tr>
				{/iteration:blogComments}
			</tbody>
		</table>
	</div>
	{/option:blogComments}

	{option:!blogComments}
	<div class="datagridHolder">
		<table cellspacing="0" class="datagrid">
			<tbody>
				<tr>
					<td>
						{$msgNoPublishedComments}
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	{/option:!blogComments}

	<div class="footer">
		<div class="buttonHolderRight">
			<a href="{$var|geturl:'comments':'blog'}" class="button"><span>{$lblAllComments|ucfirst}</span></a>
		</div>
	</div>
</div>