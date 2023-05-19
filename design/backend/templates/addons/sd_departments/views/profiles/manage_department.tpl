{** department section **}

{capture name="mainbox"}

<form action="{""|fn_url}" method="post" id="departments_form" name="departments_form" enctype="multipart/form-data">
    <input type="hidden" name="fake" value="1" />
    {include file="common/pagination.tpl" save_current_page=true save_current_url=true div_id="pagination_contents_banners"}

    {$c_url=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

    {$rev=$smarty.request.content_id|default:"pagination_contents_banners"}
    {assign var="c_icon" value="<i class=\"icon-`$search.sort_order_rev`\"></i>"}
    {assign var="c_dummy" value="<i class=\"icon-dummy\"></i>"}

    {$image_width = $settings.Thumbnails.product_admin_mini_icon_width}
    {$image_height = $settings.Thumbnails.product_admin_mini_icon_height}

{if $departments}
        <div class="table-responsive-wrapper">
            <table class="table table-middle table--relative table-responsive">
            <thead>
            <tr>
                <th width="1%" class="left mobile-hide">
                    {include file="common/check_items.tpl" class="cm-no-hide-input"}
                </th>
                <th width="6%" class="mobile-hide">&nbsp;</th>


                <th>
                    <a class="cm-ajax" href="{"`$c_url`&sort_by=name&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("name")}{if $search.sort_by == "name"}{$c_icon nofilter}{else}{$c_dummy nofilter}{/if}</a>
                </th>

                <th width="6%" class="mobile-hide">&nbsp;</th>
                <th width="10%" class="right"><a class="cm-ajax" href="{"`$c_url`&sort_by=status&sort_order=`$search.sort_order_rev`"|fn_url}" data-ca-target-id={$rev}>{__("status")}{if $search.sort_by == "status"}{$c_icon nofilter}{/if}</a></th>
            </tr>
            </thead>
    {foreach from=$departments item=department}
            <tr class="cm-row-status-{$department.status|lower}"
                    data-ca-longtap-action="setCheckBox"
                    data-ca-longtap-target="input.cm-item"
                    data-ca-id="{$department.department_id}"
                >

                {$allow_save=true}

                {if $allow_save}
                    {assign var = "no_hide_input" value="cm-no-hide-input"}
                {else}
                    {assign var = "no_hide_input" value=""}
                {/if}

                <td class="left mobile-hide">
                    <input type="checkbox" name="departments_ids[]" value="{$department.department_id}" class="cm-item {$no_hide_input}" />
                </td>

                    <td width="{$image_width + 18px}" class="products-list__image">
                        {include
                                file="common/image.tpl"
                                image=$department.main_pair.icon|default:$department.main_pair.detailed
                                image_id=$department.main_pair.image_id
                                image_width=$image_width
                                image_height=$image_height
                                href="departments.update?department_id=`$department.department_id`"|fn_url
                                image_css_class="products-list__image--img"
                                link_css_class="products-list__image--link"
                        }
                    </td>

                <td class="{$no_hide_input}" data-th="{__("name")}">
                    <a class="row-status" href="{"profiles.update_department?department_id=`$department.department_id`"|fn_url}">{$department.department}</a>
                </td>
                <td class="mobile-hide">
                    {capture name="tools_list"}
                        <li>{btn type="list" text=__("edit") href="profiles.update_department?department_id=`$department.department_id`"}</li>
                    {if $allow_save}
                        <li>{btn type="list" class="cm-confirm" text=__("delete") href="profiles.delete_department?department_id=`$department.department_id`" method="POST"}</li>
                    {/if}
                    {/capture}
                    <div class="hidden-tools">
                        {dropdown content=$smarty.capture.tools_list}
                    </div>
                </td>
                <td class="right" data-th="{__("status")}">
                    {include file="common/select_popup.tpl" id=$department.department_id status=$department.status hidden=true object_id_name="department_id" table="departments" popup_additional_class="`$no_hide_input` dropleft"}
                </td>
            </tr>
            {/foreach}
            </table>
        </div>
    {include file="common/context_menu_wrapper.tpl"
        form="banners_form"
        object="banners"
        items=$smarty.capture.banners_table
        has_permissions=$has_permission
    }

{else}
    <p class="no-items">{__("no_data")}</p>
{/if}

    {include file="common/pagination.tpl" div_id="pagination_contents_banners"}

    {capture name="buttons"}
        {capture name="tools_list"}
            {if $departments}
                <li>{btn type="delete_selected" dispatch="dispatch[profiles.delete_departments]" form="departments_form"}</li>
            {/if}
        {/capture}
        {dropdown content=$smarty.capture.tools_list class="mobile-hide"}  

    {/capture}
    {capture name="adv_buttons"}
        {include file="common/tools.tpl" 
        tool_href="profiles.add_department" 
        prefix="top" 
        hide_tools="true" 
        title=__("sd_departments.add_department") 
        icon="icon-plus"}
    {/capture}

</form>

{/capture}

{capture name="sidebar"}
    {hook name="departments:manage_sidebar"}
    {include file="common/saved_search.tpl" dispatch="profiles.manage_department" view_type="departments"}
    {include file="addons/sd_departments/views/profiles/components/department_search_form.tpl" dispatch="profiles.manage_department"}
    {/hook}
{/capture}


{hook name="departments:manage_mainbox_params"}
    {$page_title = __("sd_departments.departments")} 
    {$select_languages = true}
{/hook}

{include file="common/mainbox.tpl" 
    title=$page_title 
    content=$smarty.capture.mainbox 
    buttons=$smarty.capture.buttons 
    adv_buttons=$smarty.capture.adv_buttons 
    select_languages=$select_languages 
    sidebar=$smarty.capture.sidebar
}
