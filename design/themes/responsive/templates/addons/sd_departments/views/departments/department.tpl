<div id="product_features_{$block.block_id}">
    <div class="ty-feature">
        {if $department_data.main_pair}
            <div class="ty-feature__image">
                {include "common/image.tpl" 
                images=$department_data.main_pair}
            </div>
        {/if}

        <div class="ty-feature__description ty-wysiwyg-content">
            {$department_data.description nofilter}
        </div>
    </div>
    {if $department_data.users_ids}
        <div class="ty-feature__description ty-wysiwyg-content">
            {foreach $department_data.users_ids as $key => $department}
                {foreach $department as $key => $dep}
                    <div class="ty-feature__description ty-wysiwyg-content">
                        {$dep}
                    </div>                    
                {/foreach}
            {/foreach}
        </div>
    {/if}

<!--product_features_{$block.block_id}--></div>
{capture name="mainbox_title"}{$variant_data.variant nofilter}{/capture}
