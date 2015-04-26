{* $Id: tiki-searchindex.tpl 48283 2013-10-31 21:16:50Z jonnybradley $ *}
{extends 'layout_view.tpl'}

{block name=title}
	{title help="Search" admpage="search"}{tr}Search{/tr}{/title}
{/block}

{block name=content}
{include file='tiki-searchindex_form.tpl'}
{/block}
