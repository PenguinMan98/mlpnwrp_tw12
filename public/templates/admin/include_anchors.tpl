{*$Id: include_anchors.tpl 47975 2013-10-11 11:40:35Z jonnybradley $*}

{foreach from=$icons key=page item=info}
	{if ! $info.disabled and $info.icon}
		{self_link _icon=$info.icon _icon_class="reflect" _width="32" _height="32" _alt=$info.title page=$page _class="icon tips" _title="`$info.title`|`$info.description`"}{/self_link}
	{/if}
{/foreach}

<br class="clear" />
