<h1>Character Messages</h1>
<form name="searchMessages" method="POST">
    <div id="searchMessagesForm">
        Characters: <input type="text" id="character" name="character" {if isset($character)}value="{$character}"{/if}/>
        Text: <input type="text" name="text" {if isset($text)}value="{$text}"{/if} />
        &nbsp;&nbsp;<input type="submit" name="searchForm" value="Search!" />
    </div>
    <div id="searchChatLogResults">
    {foreach $parsed_results as $post}
        <p>{$post}</p>
    {foreachelse}
        {if isset($error)}
            {$error}
        {else}
            Nothing found for those search terms 
        {/if}
    {/foreach}
    </div> 
</form>
{literal}
<script type="text/javascript" src="../js/jquery.js" > </script>
<script type="text/javascript" src="../js/jquery-ui-1.10.2.custom.js" > </script>
<script type="text/javascript">
    $(document).ready(function() {
        
        var characters = [
{/literal}
            {foreach $characters as $char}
                "{$char}",
            {/foreach}    
{literal}
        ];
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }
        $( "#character" ).autocomplete({
                source: characters
        });
    });
</script>
{/literal}