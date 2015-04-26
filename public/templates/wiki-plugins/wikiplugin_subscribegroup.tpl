{* $Id: wikiplugin_subscribegroup.tpl 47507 2013-09-16 13:52:16Z chibaguy $ *}
{strip}
<form method="post">
<input type="hidden" name="group" value="{$subscribeGroup|escape}">
<input type="hidden" name="iSubscribeGroup" value="{$iSubscribeGroup}">
{$text|escape}
<div><input type="submit" class="btn btn-default" name="subscribeGroup" value="{tr}{$action}{/tr}"></div>
</form>
{/strip}