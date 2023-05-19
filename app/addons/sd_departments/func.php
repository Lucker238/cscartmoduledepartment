<?php

use Tygh\Enum\ObjectStatuses;
use Tygh\Registry;
use Tygh\Languages\Languages;

function fn_get_departments($params = [], $items_per_page = 0, $lang_code = CART_LANGUAGE) {
    $default_params = [
        'page' => 1,
        'items_per_page' => $items_per_page
    ];
    $params = array_merge($default_params, $params);

    if (AREA == 'C') {
        $params['status'] = ObjectStatuses::ACTIVE;
    }

    $sortings = [
        'timestamp' => '?departments.timestamp',
        'name' => '?:department_descriptions.department',
        'status' => '?:departments.status',
    ];
    $condition = $limit = $join = '';

    if (!empty($params['limit'])) {
        $limit = db_quote(' LIMIT 0, ?i', $params['limit']);
    }

    $sorting = db_sort($params, $sortings, 'name', 'asc');
 
    if (!empty($params['department_id'])) {
        $condition .= db_quote(' AND ?:departments.department_id = ?i', $params['department_id'] );
    }
 
    if (!empty($params['name'])) {
     $condition .= db_quote(' AND ?:department_descriptions.department LIKE ?l', '%' . trim($params['name']) . '%');
    }
 
    if (!empty($params['user_id'])) {
        $condition .= db_quote(' AND ?:departments.user_id = ?i', $params['user_id'] );
    }
 
    if (!empty($params['status'])) {
        $condition .= db_quote(' AND ?:departments.status = ?s', $params['status']);
    }
 
    $fields = [
        '?:departments.*',
        '?:department_descriptions.department',
        '?:department_descriptions.description'
    ];
 
    $join .= db_quote(' LEFT JOIN ?:department_descriptions ON ?:department_descriptions.department_id = ?:departments.department_id AND ?:department_descriptions.lang_code = ?s', $lang_code);
 
    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field("SELECT COUNT(*) FROM ?:departments $join WHERE 1 $condition");
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }
 
    $departments = db_get_hash_array(
        "SELECT ?p FROM ?:departments " .
        $join .
        "WHERE 1 ?p ?p ?p",
        'department_id', implode(', ', $fields), $condition, $sorting, $limit
    );
 
    $department_image_ids = array_keys($departments);
    $images = fn_get_image_pairs($department_image_ids, 'department', 'M', true, false, $lang_code);
 
    foreach ($departments as $department_id => $department) {
       $departments[$department_id]['main_pair'] = !empty($images[$department_id]) ? reset($images[$department_id]) : [];
    }
 
    return [$departments, $params];
 }
 
function fn_get_department_data($department_id = 0, $lang_code = CART_LANGUAGE) {
    $departments = [];
    if(!empty($department_id)) {
        list($departments) = fn_get_departments([
            'department_id' => $department_id
        ], 1, $lang_code);
        $department = !empty($departments) ? reset($departments) : [];
        if(!empty($departments)) {
            $department = reset($departments);
            $department['users_ids'] = fn_department_get_links($department['department_id']);
        }
    }
    return $department;
}
 
function fn_update_department($data, $department_id, $lang_code = DESCR_SL) {
    if (isset($data['timestamp'])) {
        $data['timestamp'] = fn_parse_date($data['timestamp']);
    }

    if (!empty($department_id)) {
        db_query("UPDATE ?:departments SET ?u WHERE department_id = ?i", $data, $department_id);
        db_query("UPDATE ?:department_descriptions SET ?u WHERE department_id = ?i AND lang_code = ?s", $data, $department_id, $lang_code);

    } else {
        $department_id = $data['department_id'] = db_replace_into('departments', $data);
        foreach (Languages::getAll() as $data['lang_code'] => $v) {
            db_query("REPLACE INTO ?:department_descriptions ?e", $data);
        }
    }

    if (!empty($department_id)) {
        fn_attach_image_pairs('department', 'department', $department_id, $lang_code);
    }
    
    $users_ids = !empty($data['users']) ? $data['users'] : '';
    fn_department_delete_links($department_id);
    fn_department_add_links($department_id, $users_ids);
    return $department_id;
}
 
function fn_delete_department($department_id) {
    if (!empty($department_id)) {
        fn_department_delete_links($department_id);
        $res = db_query('DELETE FROM ?:departments WHERE department_id = ?i', $department_id);
        db_query('DELETE FROM ?:department_descriptions WHERE department_id = ?i', $department_id);
    }
}

function fn_department_delete_links($department_id) {
    db_query('DELETE FROM ?:department_links WHERE department_id = ?i', $department_id);
}

function fn_department_add_links($department_id, $users_ids) {
    if(!empty($users_ids)) {
        $users_ids = explode(',', $users_ids);
        foreach($users_ids as $user_id) {
            $user_data = fn_get_user_info($user_id);
            db_query("REPLACE INTO ?:department_links ?e", [
                'department_id' => $department_id,
                'customers' => $user_data['firstname'] . ' ' . $user_data['lastname'] . '    ' . $user_data['email'],
            ]);
        }
    }
}

function fn_department_get_links($department_id) {
    return !empty($department_id) ? db_get_array('SELECT customers FROM ?:department_links WHERE department_id = ?i', $department_id) : [];
}
