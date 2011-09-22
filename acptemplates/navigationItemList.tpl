{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	document.observe("dom:loaded", function() {
		var navigationItemList = $('navigationItemList');
		if (navigationItemList) {
			navigationItemList.addClassName('dragable');
			var startValue = {if $sortOrder == 'ASC'}{@$itemsPerPage} * ({@$pageNo} - 1) + 1{else}{$items} - {@$itemsPerPage} * ({@$pageNo} - 1){/if};
			
			Sortable.create(navigationItemList, { 
				tag: 'tr',
				onUpdate: function(list) {
					var rows = list.select('tr');
					var showOrder = 0;
					var newShowOrder = 0;
					rows.each(function(row, i) {
						row.className = 'container-' + (i % 2 == 0 ? '1' : '2') + (row.hasClassName('marked') ? ' marked' : '');
						showOrder = row.select('.columnNumbers')[0];
						newShowOrder = {if $sortOrder == 'ASC'}i + startValue{else}startValue - i{/if};
						if (newShowOrder != showOrder.innerHTML) {
							showOrder.update(newShowOrder);
							new Ajax.Request('index.php?action=NavigationItemSort&navigationItemID='+row.id.gsub('navigationItemRow_', '')+SID_ARG_2ND, { method: 'post', parameters: { showOrder: newShowOrder } } );
						}
					});
				}
			});
		}	
	});
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/navigationItemL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.navigation.item.view{/lang}</h2>
		{if $navigationID}<p>{lang}{$navigation->title}{/lang}</p>{/if}
	</div>
</div>

{if $deletedNavigationItemID}
	<p class="success">{lang}wcf.acp.navigation.item.delete.success{/lang}</p>	
{/if}

{if $navigations|count}
	<div class="contentHeader">
		{pages print=true assign=pagesLinks link="index.php?page=NavigationItemList&navigationID=$navigationID&pageNo=%d&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
		{if $this->user->getPermission('admin.navigation.canEditNavigation')}
			<div class="largeButtons">
				<ul><li><a href="index.php?form=NavigationItemAdd&amp;navigationID={@$navigationID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/navigationItemAddM.png" alt="" title="{lang}wcf.acp.navigation.item.add{/lang}" /> <span>{lang}wcf.acp.navigation.item.add{/lang}</span></a></li></ul>
			</div>
		{/if}
	</div>
	
	<fieldset>
		<legend>{lang}wcf.acp.navigation.item.navigation{/lang}</legend>
		<div class="formElement" id="navigationDiv">
			<div class="formFieldLabel">
				<label for="navigationChange">{lang}wcf.acp.navigation.item.navigation{/lang}</label>
			</div>
			<div class="formField">
				<select id="navigationChange" onchange="document.location.href=fixURL('index.php?page=NavigationItemList&amp;navigationID='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
					<option value="0"></option>
					{htmloptions options=$navigations selected=$navigationID}
				</select>
			</div>
			<div class="formFieldDesc hidden" id="navigationHelpMessage">
				{lang}wcf.acp.navigation.item.navigation.description{/lang}
			</div>
		</div>
		<script type="text/javascript">//<![CDATA[
			inlineHelp.register('navigation');
		//]]></script>
	</fieldset>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.navigation.item.view.count.noNavigations{/lang}</p>
		</div>
	</div>
{/if}

{if $navigationID}
	{if $navigationItems|count}
		<div class="border titleBarPanel">
			<div class="containerHead"><h3>{lang}wcf.acp.navigation.item.view.count{/lang}</h3></div>
		</div>
		<div class="border borderMarginRemove">
			<table class="tableList">
				<thead>
					<tr class="tableHead">
						<th class="columnNavigationItemID{if $sortField == 'navigationItemID'} active{/if}" colspan="2"><div><a href="index.php?page=NavigationItemList&amp;navigationID={@$navigationID}&amp;pageNo={@$pageNo}&amp;sortField=navigationItemID&amp;sortOrder={if $sortField == 'navigationItemID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.navigation.item.navigationItemID{/lang}{if $sortField == 'navigationItemID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnNavigationItem{if $sortField == 'navigationItem'} active{/if}"><div><a href="index.php?page=NavigationItemList&amp;navigationID={@$navigationID}&amp;pageNo={@$pageNo}&amp;sortField=navigationItem&amp;sortOrder={if $sortField == 'navigationItem' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.navigation.item.navigationItem{/lang}{if $sortField == 'navigationItem'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnShowOrder{if $sortField == 'showOrder'} active{/if}"><div><a href="index.php?page=NavigationItemList&amp;navigationID={@$navigationID}&amp;pageNo={@$pageNo}&amp;sortField=showOrder&amp;sortOrder={if $sortField == 'showOrder' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.navigation.item.showOrder{/lang}{if $sortField == 'showOrder'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						
						{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
					</tr>
				</thead>
				<tbody id="navigationItemList">
					{foreach from=$navigationItems item=navigationItem}
						<tr class="{cycle values="container-1,container-2"}" id="navigationItemRow_{@$navigationItem->navigationItemID}">
							<td class="columnIcon">
								{if $this->user->getPermission('admin.navigation.canEditNavigationItem')}
									<a href="index.php?form=NavigationItemEdit&amp;navigationItemID={@$navigationItem->navigationItemID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.navigation.item.edit{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.navigation.item.edit{/lang}" />
								{/if}
								{if $this->user->getPermission('admin.navigation.canDeleteNavigationItem')}
									<a onclick="return confirm('{lang}wcf.acp.navigation.item.delete.sure{/lang}')" href="index.php?action=NavigationItemDelete&amp;navigationItemID={@$navigationItem->navigationItemID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.navigation.item.delete{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.navigation.item.delete{/lang}" />
								{/if}
								
								{if $additionalButtons.$navigationItem->navigationItemID|isset}{@$additionalButtons.$navigationItem->navigationItemID}{/if}
							</td>
							<td class="columnNavigationItemID columnID">{@$navigationItem->navigationItemID}</td>
							<td class="columnNavigationItem columnText">
								{if $this->user->getPermission('admin.navigation.canEditNavigationItem')}
									<a href="index.php?form=NavigationItemEdit&amp;navigationItemID={@$navigationItem->navigationItemID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$navigationItem->getTitle()}</a>
								{else}
									{$navigationItem->getTitle()}
								{/if}
							</td>
							<td class="columnShowOrder columnNumbers">{@$navigationItem->showOrder}</td>
							
							{if $additionalColumns.$navigationItem->navigationItemID|isset}{@$additionalColumns.$snavigationItem->navigationItemID}{/if}
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
		
		<div class="contentFooter">
			{@$pagesLinks}
			
			{if $this->user->getPermission('admin.navigation.canAddNavigationItem')}
				<div class="largeButtons">
					<ul><li><a href="index.php?form=NavigationItemAdd&amp;navigationID={@$navigationID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/navigationItemAddM.png" alt="" title="{lang}wcf.acp.navigation.item.add{/lang}" /> <span>{lang}wcf.acp.navigation.item.add{/lang}</span></a></li></ul>
				</div>
			{/if}
		</div>
	{else}
		<div class="border content">
			<div class="container-1">
				<p>{lang}wcf.acp.navigation.item.view.count.noNavigationItems{/lang}</p>
			</div>
		</div>
	{/if}
{/if}

{include file='footer'}