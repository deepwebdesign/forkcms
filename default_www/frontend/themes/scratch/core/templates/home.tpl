{include:file='{$FRONTEND_CORE_PATH}/layout/templates/head.tpl'}

<body id="home" class="{$LANGUAGE} frontend">
	<div id="container">

		<div id="header">
			<h1><a href="/">{$siteTitle}</a></h1>
			<div id="language">
				{include:file='{$FRONTEND_CORE_PATH}/layout/templates/languages.tpl'}
			</div>
			<div id="navigation">
				{$var|getnavigation:'page':0:1}
			</div>
		</div>

		<div id="main">

			<div id="intro">
				{* Block 1 *}
				{option:block1IsHTML}
					<div class="content">
						{$block1}
					</div>
				{/option:block1IsHTML}
				{option:!block1IsHTML}
					{include:file='{$block1}'}
				{/option:!block1IsHTML}
			</div>

			<div id="content">

				{* Block 2 *}
				{option:block2IsHTML}
					<div class="content">
						{$block2}
					</div>
				{/option:block2IsHTML}
				{option:!block2IsHTML}
					{include:file='{$block2}'}
				{/option:!block2IsHTML}

				{* Block 3 *}
				{option:block3IsHTML}
					<div class="content">
						{$block3}
					</div>
				{/option:block3IsHTML}
				{option:!block3IsHTML}
					{include:file='{$block3}'}
				{/option:!block3IsHTML}

			</div>
		</div>

		{include:file='{$FRONTEND_CORE_PATH}/layout/templates/footer.tpl'}
	</div>
</body>
</html>