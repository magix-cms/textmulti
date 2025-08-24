{if isset($textmulti) && $textmulti != null}
    <section id="textmulti" class="clearfix">
        <div class="container">
            {foreach $textmulti as $TM}
                <p class="h1">{$TM.title}</p>
                {$TM.desc}
            {/foreach}
        </div>
    </section>
{/if}