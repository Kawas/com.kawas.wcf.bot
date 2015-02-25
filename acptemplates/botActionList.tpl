{include file='header' pageTitle='wcf.acp.bot.action.list'}

<header class="boxHeadline">
	<h1>{lang}wcf.acp.bot.action.list{/lang}</h1>
	
	<script data-relocate="true">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\bot\\action\\BotActionAction', '.jsBotActionRow');
			new WCF.Action.Toggle('wcf\\data\\bot\\action\\BotActionAction', $('.jsBotActionRow'));
		});
		//]]>
	</script>

</header>
{if !$bot->isEnabled()}
	<p class="info">{lang}wcf.acp.bot.notEnabled{/lang}</p>
{/if}


<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="BotActionList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	
	<nav>
		<ul>
			<li><a href="{link controller='BotActionAdd'}{/link}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}wcf.acp.bot.action.add{/lang}</span></a></li>
			
			{event name='contentNavigationButtonsTop'}
		</ul>
	</nav>
</div>

{if $objects|count}
	<div class="tabularBox tabularBoxTitle marginTop">
		<header>
			<h2>{lang}wcf.acp.bot.action.list{/lang} <span class="badge badgeInverse">{#$items}</span></h2>
		</header>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnID{if $sortField == 'actionID'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='BotActionList'}pageNo={@$pageNo}&sortField=actionID&sortOrder={if $sortField == 'actionID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.global.objectID{/lang}</a></th>
          <th class="columnTitle{if $sortField == 'actionName'} active {@$sortOrder}{/if}"><a href="{link controller='BotActionList'}pageNo={@$pageNo}&sortField=actionName&sortOrder={if $sortField == 'actionName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.bot.action.name{/lang}</a></th>
          <th class="columnEventName{if $sortField == 'eventName'} active {@$sortOrder}{/if}"><a href="{link controller='BotActionList'}pageNo={@$pageNo}&sortField=eventName&sortOrder={if $sortField == 'eventName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.bot.action.event{/lang}</a></th>
          <th class="columnActionTypeName{if $sortField == 'actionTypeName'} active {@$sortOrder}{/if}"><a href="{link controller='BotActionList'}pageNo={@$pageNo}&sortField=actionTypeName&sortOrder={if $sortField == 'actionTypeName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.bot.action.type{/lang}</a></th>

					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=action}
					<tr class="jsBotActionRow">
						<td class="columnIcon">
              <span class="icon icon16 icon-check{if $action->isDisabled}-empty{/if} jsToggleButton jsTooltip pointer" title="{lang}wcf.global.button.{if $action->isDisabled}enable{else}disable{/if}{/lang}" data-object-id="{@$action->actionID}" data-disable-message="{lang}wcf.global.button.disable{/lang}" data-enable-message="{lang}wcf.global.button.enable{/lang}"></span>
							<a href="{link controller='BotActionEdit' id=$action->getObjectID()}{/link}" title="{lang}wcf.global.button.edit{/lang}" class="jsTooltip"><span class="icon icon16 icon-pencil"></span></a>
							{if $action->isTemplate}
                <span class="icon icon16 icon-remove disabled" title="{lang}wcf.global.button.delete{/lang}"></span>
              {else}
                <span class="icon icon16 icon-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{@$action->actionID}" data-confirm-message="{lang}wcf.acp.bot.actionList.sure{/lang}"></span>
              {/if}
		
							{event name='rowButtons'}
						</td>
						<td class="columnID">{$action->getObjectID()}</td>
						<td class="columnTitle">{$action->actionName|language}</td>
            <td class="columnEventName">{lang}wcf.acp.bot.event.{$action->eventName}{/lang}</td>
            <td class="columnActionTypeName">{lang}wcf.acp.bot.action.type.{$action->actionTypeName}{/lang}</td>

						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
		
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		<nav>
			<ul>
				<li><a href="{link controller='BotActionAdd'}{/link}" class="button"><span class="icon icon16 icon-plus"></span> <span>{lang}wcf.acp.bot.action.add{/lang}</span></a></li>
				
				{event name='contentNavigationButtonsBottom'}
			</ul>
		</nav>
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
