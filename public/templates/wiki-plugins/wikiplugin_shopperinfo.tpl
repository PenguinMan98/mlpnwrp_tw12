{* $Id: wikiplugin_shopperinfo.tpl 47507 2013-09-16 13:52:16Z chibaguy $ *}
<form method="post" action="{query _type=relative _keepall=y}" style="display: inline;">
	{section name=v loop=$values}
	{$values[v].label|escape}: <input type="text" size="20" name="{$values[v].name}" value="{$values[v].current}">
	<br>
	{/section}
	<input type="submit" class="btn btn-default" name="shopperinfo" value="{tr}Submit{/tr}">
</form>

