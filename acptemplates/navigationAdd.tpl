{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/navigation{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.navigation.{@$action}{/lang}</h2>
		{if $navigationID|isset}<p>{lang}{$navigation->title}{/lang}</p>{/if}
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.navigation.{@$action}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=NavigationList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.navigation.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/navigationM.png" alt="" /> <span>{lang}wcf.acp.menu.link.navigation.view{/lang}</span></a></li></ul>
	</div>
</div>

<form method="post" action="index.php?form=Navigation{@$action|ucfirst}">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.navigation.data{/lang}</legend>
				
				<div class="formElement{if $errorField == 'title'} formError{/if}">
					<div class="formFieldLabel">
						<label for="title">{lang}wcf.acp.navigation.title{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="title" name="title" value="{$title}" />
						{if $errorField == 'title'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
				</div>
				
				{if $additionalDataFields|isset}{@$additionalDataFields}{/if}
			</fieldset>
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
		
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		{if $navigationID|isset}<input type="hidden" name="navigationID" value="{@$navigationID}" />{/if}
 	</div>
</form>

{include file='footer'}