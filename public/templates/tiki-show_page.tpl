{* $Id: tiki-show_page.tpl 47221 2013-08-22 20:47:21Z arildb $ *}
{extends 'layout_view.tpl'}

{* Separate the content display from the display of the whole page. 
Used to support printing, which use the tiki-show_content.tpl directly.
Note: The show content block must be defined at rooot level to use the include. AB *}
{block name=show_content}{include file='tiki-show_content.tpl'}{/block}
