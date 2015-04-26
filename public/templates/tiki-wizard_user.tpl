{* $Id: tiki-wizard_user.tpl 49682 2014-02-03 16:43:09Z xavidp $ *}

{title}{tr}User Wizard{/tr}{/title}

<form action="tiki-wizard_user.php" method="post">
{include file="wizard/wizard_bar_user.tpl"}
<div id="wizardBody"> 
<table class="adminWizardTable">
	<tr>
	{if !empty($wizard_toc)}
		<td class="adminWizardTOC">
			<span class="adminWizardTOCTitle">{tr}Wizard Steps{/tr}</span><br>
			{$wizard_toc}
		</td>
	{/if}
		<td class="adminWizardBody">
			{include file="{$wizardBody}"}
		</td>
	</tr>
</table>
</div>
{include file="wizard/wizard_bar_user.tpl"}
</form>
