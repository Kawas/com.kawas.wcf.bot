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

<form method="post" action="{link controller='BotActionEdit' id=$botAction->actionID}{/link}">
	<div class="container containerPadding marginTop">
    <fieldset>
        <legend>{lang}wcf.acp.bot.action.name{/lang}</legend>
        <dl>
          <dt><label for="actionName">{lang}wcf.acp.bot.action.name{/lang}</label></dt>
          <dd>
            {if $botAction->isTemplate}{$actionName|language}{/if}
            <input id="actionName" name="actionName" value="{if $botAction->isTemplate}{$actionName}{else}{$actionName|language}{/if}" type="{if $botAction->isTemplate}hidden{else}text{/if}" />
			{if $errorField == 'actionName'}
				<small class="innerError">
					{if $errorType == 'empty'}
						{lang}wcf.global.form.error.empty{/lang}
					{/if}
				</small>
			{/if}
          </dd>
          <dt><label for="actionName">{lang}wcf.acp.bot.action.event{/lang}</label></dt>
          <dd>
            {lang}wcf.acp.bot.event.{$botAction->eventName}{/lang}
            {hascontent}<br /><small>{content}{lang}wcf.acp.bot.event.{$botAction->eventName}.description{/lang}{/content}</small>{/hascontent}
          </dd>
          <dt><label for="actionName">{lang}wcf.acp.bot.action.type{/lang}</label></dt>
          <dd>
            {lang}wcf.acp.bot.action.type.{$botAction->actionTypeName}{/lang}
            {hascontent}<br /><small>{content}{lang}wcf.acp.bot.action.type.{$botAction->actionTypeName}.description{/lang}{/content}</small>{/hascontent}
          </dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>{lang}wcf.acp.bot.action.options{/lang}</legend>
        {include file='optionFieldList'}
    </fieldset>


		{event name='fieldsets'}
	</div>
	
	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s" />
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

{include file='footer'}
