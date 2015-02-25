{include file='header'}


<header class="boxHeadline">
	<h1>{lang}wcf.acp.bot.action.{$action}{/lang}</h1>
</header>

{if !$bot->isEnabled()}
	<p class="info">{lang}wcf.acp.bot.notEnabled{/lang}</p>
{/if}

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}



<div class="contentNavigation">
	<nav>
		<ul>
				
			{event name='contentNavigationButtons'}
		</ul>
	</nav>
</div>

<form method="post" action="{if $action == 'add'}{link controller='botActionAdd'}{/link}{else}{link controller='botActionEdit'}{/link}{/if}">
	<div class="container containerPadding marginTop">
      <fieldset>
          <legend>{lang}wcf.acp.bot.action.general{/lang}</legend>
          <dl>
            <dt><label for="actionName">{lang}wcf.acp.bot.action.name{/lang}</label></dt>
            <dd>
              	<input id="actionName" name="actionName" value="" type="text" />
		{if $errorField == 'actionName'}
			<small class="innerError">
				{if $errorType == 'empty'}
					{lang}wcf.global.form.error.empty{/lang}
				{/if}
			</small>
		{/if}
            </dd>
          </dl>
      </fieldset>
      <fieldset>
          <legend>{lang}wcf.acp.bot.action.eventChoose{/lang}</legend>
          
          <dl>
            <dt><label for="eventID">{lang}wcf.acp.bot.action.event{/lang}</label></dt>
            <dd>
                {foreach from=$events item=$event}
					<label><input type="radio" name="eventID" value="{$event->eventID}" {if $eventID == $event->eventID}checked="checked"{/if}/ >{lang}wcf.acp.bot.event.{$event->eventName}{/lang}</label>
					{hascontent}<small>{content}{lang __optional=true}wcf.acp.bot.event.{$event->eventName}.description{/lang}{/content}</small>{/hascontent}
                {/foreach}
            </dd>
          </dl>
          <dl>
            <dt><label for="useEventParameters">{lang}wcf.acp.bot.action.useEventParameters{/lang}</label></dt>
            <dd>
              <input id="useEventParameters" name="useEventParameters"{if $useEventParameters} checked="checked"{/if} value="0" type="checkbox" />
            </dd>
          </dl>
      </fieldset>
      <fieldset>
          <legend>{lang}wcf.acp.bot.action.typeChoose{/lang}</legend>
          
          <dl>
            <dt><label for="actionTypeID">{lang}wcf.acp.bot.action.type{/lang}</label></dt>
            <dd>
              {foreach from=$actionTypes item=$actionType}
					<label><input type="radio" name="actionTypeID" value="{$actionType->actionTypeID}" {if $actionTypeID == $actionType->actionTypeID}checked="checked"{/if}/ >{lang}wcf.acp.bot.action.type.{$actionType->actionTypeName}{/lang}</label>
					{hascontent}<small>{content}{lang __optional=true}wcf.acp.bot.action.type.{$actionType->actionTypeName}.description{/lang}{/content}</small>{/hascontent}
               {/foreach}
            </dd>
          </dl>
      </fieldset>

		{event name='fieldsets'}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
