{if $departments}
    {script src="js/tygh/exceptions.js"}
    {if !$no_pagination}
        {include "common/pagination.tpl"}
    {/if}

    {if !$show_empty}
        {split data=$departments size=$columns|default:"2" assign="splitted_departments"}
    {else}
        {split data=$departments size=$columns|default:"2" assign="splitted_departments" skip_complete=true}
    {/if}

    {math equation="100 / x" x=$columns|default:"2" assign="cell_width"}

    {* FIXME: Don't move this file *}
    {script src="js/tygh/product_image_gallery.js"}

    <div class="grid-list">
        {strip}
            {foreach $splitted_departments as $key => $sdepartments}
                {foreach $sdepartments as $key => $department}
                    <div class="ty-column{$columns}">
                        {if $department}
                            {$obj_id = $department.department_id}
                            {$obj_id_prefix = "`$obj_prefix``$department.department_id`"}
                            <div class="ty-grid-list__item ty-quick-view-button__wrapper ">
                                <div class="ty-grid-list__image">
                                    <a href="{"departments.department?department_id={$department.department_id}"|fn_url}">
                                        {include file="common/image.tpl" 
                                        no_ids=true 
                                        images=$department.main_pair
                                        image_width=$settings.Thumbnails.product_lists_thumbnail_width 
                                        image_height=$settings.Thumbnails.product_lists_thumbnail_height 
                                        lazy_load=false}
                                    </a>
                                </div>
                                <div class="ty-grid-list__item-name">
                                    <bdi>
                                        <a href="{"departments.department?department_id={$department.department_id}"|fn_url}" class="product-title" title="{$department.department}">{$department.department}</a>    
                                    </bdi>
                                 </div>
                                 <div>
                                     <td width="{$person_name_col_width}" 
                                     class="row-status wrap" 
                                     data-th="{__("person_name")}">
                                     {$headuser_info = fn_get_user_short_info($department.user_id)}
                                     {if $headuser_info.firstname || $headuser_info.lastname}
                                     {$headuser_info.lastname} {$headuser_info.firstname}
                                     {else}-{/if}
                                </div>
                            </div>
                        {/if}
                    </div>
                {/foreach}
            {/foreach}
        {/strip}
    </div>

    {if !$no_pagination}
        {include "common/pagination.tpl"}
    {/if}

{/if}

{capture name="mainbox_title"}{$title}{/capture}
