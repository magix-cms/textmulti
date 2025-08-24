<div class="row">
    <form id="edit_textmulti" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}{if isset($smarty.get.edit)}&amp;action=edit&amp;edit={$smarty.get.edit}{/if}&amp;plugin={$smarty.get.plugin}&amp;mod_action={if !$edit}add{else}edit{/if}" method="post" class="validate_form{if !$edit} add_form collapse in{else} edit_form{/if} col-ph-12 col-lg-12">
        {include file="language/brick/dropdown-lang.tpl"}
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <div role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <fieldset>
                                <legend>Texte</legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                        <div class="form-group">
                                            <label for="title_textmulti_{$id}">{#title_textmulti#|ucfirst} :</label>
                                            <input type="text" class="form-control" id="title_textmulti_{$id}" name="content[{$id}][title_textmulti]" value="{$textmulti.content[{$id}].title_textmulti}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="desc_textmulti_{$id}">{#desc_textmulti#|ucfirst} :</label>
                                            <textarea id="desc_textmulti_{$id}" name="content[{$id}][desc_textmulti]" class="form-control mceEditor">{call name=cleantextarea field=$textmulti.content[{$id}].desc_textmulti}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
                                        <div class="form-group">
                                            <label for="published_textmulti_{$id}">Statut</label>
                                            <input id="published_textmulti_{$id}" data-toggle="toggle" type="checkbox" name="content[{$id}][published_textmulti]" data-on="Publiée" data-off="Brouillon" data-onstyle="success" data-offstyle="danger"{if (!isset($textmulti) && $iso@first) || $textmulti.content[{$id}].published_textmulti} checked{/if}>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <fieldset>
            <legend>Enregistrer</legend>
            {if $edit}
                <input type="hidden" name="content[id]" value="{$textmulti.id_textmulti}" />
            {/if}
            <button class="btn btn-main-theme" type="submit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>