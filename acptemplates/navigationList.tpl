{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/navigationL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.navigation.view{/lang}</h2>
	</div>
</div>

{if $deletedNavigationID}
	<p class="success">{lang}wcf.acp.navigation.delete.success{/lang}</p>	
{/if}<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=NavigationList&pageNo=%d&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
	{if $this->user->getPermission('admin.navigation.canAddNavigation')}
		<div class="largeButtons">
			<ul><li><a href="index.php?form=NavigationAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/navigationAddM.png" alt="" title="{lang}wcf.acp.navigation.add{/lang}" /> <span>{lang}wcf.acp.navigation.add{/lang}</span></a></li></ul>
		</div>
	{/if}
</div>

{if $navigations|count}
	<div class="border titleBarPanel">
		<div class="containerHead"><h3>{lang}wcf.acp.navigation.view.count{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th class="columnNavigationID{if $sortField == 'navigationID'} active{/if}" colspan="2"><div><a href="index.php?page=NavigationList&amp;pageNo={@$pageNo}&amp;sortField=navigationID&amp;sortOrder={if $sortField == 'navigationID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.navigation.navigationID{/lang}{if $sortField == 'navigationID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnTitle{if $sortField == 'title'} active{/if}"><div><a href="index.php?page=NavigationList&amp;pageNo={@$pageNo}&amp;sortField=title&amp;sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.navigation.title{/lang}{if $sortField == 'title'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnNavigationItems{if $sortField == 'navigationItems'} active{/if}"><div><a href="index.php?page=NavigationList&amp;pageNo={@$pageNo}&amp;sortField=navigationItems&amp;sortOrder={if $sortField == 'navigationItems' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.navigation.navigationItems{/lang}{if $sortField == 'navigationItems'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					
					{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
				</tr>
			</thead>
			<tbody id="navigationList">
				{foreach from=$navigations item=navigation}
					<tr class="{cycle values="container-1,container-2"}">
						<td class="columnIcon">
							{if $this->user->getPermission('admin.navigation.canEditNavigation')}
								<a href="index.php?form=NavigationEdit&amp;navigationID={@$navigation->navigationID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.navigation.edit{/lang}" /></a>
							{else}
								<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.navigation.edit{/lang}" />
							{/if}
							{if $this->user->getPermission('admin.navigation.canDeleteNavigation')}
								<a onclick="return confirm('{lang}wcf.acp.navigation.delete.sure{/lang}')" href="index.php?action=NavigationDelete&amp;navigationID={@$navigation->navigationID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.navigation.delete{/lang}" /></a>
							{else}
								<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.navigation.delete{/lang}" />
							{/if}
							
							{if $additionalButtons.$navigation->navigationID|isset}{@$additionalButtons.$navigation->navigationID}{/if}
						</td>
						<td class="columnNavigationID columnID">{@$navigation->navigationID}</td>
						<td class="columnTitle columnText">
							{if $this->user->getPermission('admin.navigation.canEditNavigation')}
								<a href="index.php?form=NavigationEdit&amp;navigationID={@$navigation->navigationID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$navigation->title}</a>
							{else}
								{$navigation->title}
							{/if}
						</td>
						<td class="columnNavigationItems columnNumbers"><a href="index.php?page=NavigationItemList&amp;navigationID={@$navigation->navigationID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{@$navigation->navigationItems}</a></td>
						
						{if $additionalColumns.$navigation->navigationID|isset}{@$additionalColumns.$navigation->navigationID}{/if}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	
	<div class="contentFooter">
		{@$pagesLinks}
		
		{if $this->user->getPermission('admin.navigation.canAddNavigation')}
			<div class="largeButtons">
				<ul><li><a href="index.php?form=NavigationAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/navigationAddM.png" alt="" title="{lang}wcf.acp.navigation.add{/lang}" /> <span>{lang}wcf.acp.navigation.add{/lang}</span></a></li></ul>
			</div>
		{/if}
	</div>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.navigation.view.count.noNavigations{/lang}</p>
		</div>
	</div>
{/if}

{include file='footer'}