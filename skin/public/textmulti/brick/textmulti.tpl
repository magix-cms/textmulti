{if isset($textmulti) && $textmulti != null}
    <section id="textmulti" class="clearfix">
        <div class="container">
            {foreach $textmulti as $TM}
                <div class="textmulti-body">
                    <p class="h1">{$TM.title}</p>
                    {$TM.desc}
                </div>
            {/foreach}
        </div>
    </section>
{/if}