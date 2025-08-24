{* if $smarty.get.controller === 'category'}{$dir = 'catalog/'|cat:$smarty.get.controller}{else}{$dir = $smarty.get.controller}{/if}
{extends file="{$dir}/edit.tpl"*}
{extends file="{$extends}"}
{block name="stylesheets"}
    {headlink rel="stylesheet" href="/{baseadmin}/min/?f=plugins/textmulti/css/admin.min.css" media="screen"}
{/block}
{block name="plugin:content"}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
        {if $debug}
            {$debug}
        {/if}
        {include file="mod/textmulti.tpl" controller="textmulti"}
    {/if}
{/block}

{block name="foot"}
    {include file="section/footer/editor.tpl"}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=plugins/textmulti/js/admin.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
{/block}