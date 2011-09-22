{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/navigationItem{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.navigation.item.{@$action}{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.navigation.item.{@$action}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=NavigationItemList{if $navigationID}&amp;navigationID={@$navigationID}{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.navigation.items{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/navigationItemM.png" alt="" /> <span>{lang}wcf.acp.navigation.items{/lang}</span></a></li></ul>
	</div>
</div>

<form method="post" action="index.php?form=NavigationItem{@$action|ucfirst}{if $action == 'add'}&amp;navigationID={@$navigationID}{/if}">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.navigation.item.classification{/lang}</legend>
						
				<div class="formElement" id="navigationIDDiv">
					<div class="formFieldLabel">
						<label for="navigationID">{lang}wcf.acp.navigation.item.navigationID{/lang}</label>
					</div>
					<div class="formField">
						<select name="navigationID" id="navigationID">
							{htmlOptions options=$navigations selected=$navigationID}
						</select>
					</div>
					<div class="formFieldDesc hidden" id="navigationIDHelpMessage">
						{lang}wcf.acp.navigation.item.navigationID.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('navigationID');
				//]]></script>
				
				<div class="formElement" id="showOrderDiv">
					<div class="formFieldLabel">
						<label for="showOrder">{lang}wcf.acp.navigation.item.showOrder{/lang}</label>
					</div>
					<div class="formField">	
						<input type="text" class="inputText" name="showOrder" id="showOrder" value="{$showOrder}" />
					</div>
					<div class="formFieldDesc hidden" id="showOrderHelpMessage">
						{lang}wcf.acp.navigation.item.showOrder.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('showOrder');
				//]]></script>
				
				{if $additionalClassificationFields|isset}{@$additionalClassificationFields}{/if}
			</fieldset>
						
			<fieldset>
				<legend>{lang}wcf.acp.navigation.item.data{/lang}</legend>
				
				{if $action == 'edit'}
					<div class="formElement">
						<div class="formFieldLabel">
							<label for="languageID">{lang}wcf.acp.navigation.item.language{/lang}</label>
						</div>
						<div class="formField">
							<select name="languageID" id="languageID" onchange="location.href='index.php?form=NavigationItemEdit&amp;navigationItemID={@$navigationItemID}&amp;languageID='+this.value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}'">
								{foreach from=$languages key=availableLanguageID item=languageCode}
									<option value="{@$availableLanguageID}"{if $availableLanguageID == $languageID} selected="selected"{/if}>{lang}wcf.global.language.{@$languageCode}{/lang}</option>
								{/foreach}
							</select>
						</div>
						<div class="formFieldDesc hidden" id="languageIDHelpMessage">
							{lang}wcf.acp.navigation.item.language.description{/lang}
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('languageID');
					//]]></script>
				{/if}
				
				<div class="formElement{if $errorField == 'title'} formError{/if}" id="titleDiv">
					<div class="formFieldLabel">
						<label for="title">{lang}wcf.acp.navigation.item.title{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="title" name="title" value="{$title}" />
						{if $errorField == 'title'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="titleHelpMessage">
						{lang}wcf.acp.navigation.item.title.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('title');
				//]]></script>
				
				<div class="formElement{if $errorField == 'url'} formError{/if}" id="urlDiv">
					<div class="formFieldLabel">
						<label for="url">{lang}wcf.acp.navigation.item.url{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="url" name="url" value="{$url}" />
						{if $errorField == 'url'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="urlHelpMessage">
						{lang}wcf.acp.navigation.item.url.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('url');
				//]]></script>
				
				{if $additionalDataFields|isset}{@$additionalDataFields}{/if}
			</fieldset>		
							
			<fieldset>
				<legend>{lang}wcf.acp.navigation.item.display{/lang}</legend>
				
				<div class="formElement{if $errorField == 'icon'} formError{/if}" id="iconDiv">
					<div class="formFieldLabel">
						<label for="icon">{lang}wcf.acp.navigation.item.icon{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="icon" name="icon" value="{$icon}" />
						{if $errorField == 'icon'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="iconHelpMessage">
						{lang}wcf.acp.navigation.item.icon.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('icon');
				//]]></script>
				
				{if $additionalDisplayFields|isset}{@$additionalDisplayFields}{/if}
			</fieldset>
			
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
	
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		{if $navigationItemID|isset}<input type="hidden" name="navigationItemID" value="{@$navigationItemID}" />{/if}
 	</div>
</form>

{include file='footer'}