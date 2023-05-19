<?php
use Tygh\Registry;

defined('BOOTSTRAP') or die('Access denied');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($mode == 'update_department') {

        $department_id = !empty($_REQUEST["department_id"]) ? $_REQUEST['department_id'] : 0;
        $data = !empty($_REQUEST['department_data']) ? $_REQUEST['department_data'] : [];
        $department_id = fn_update_department($data, $department_id);

        if (!empty($department_id)) {
            $url = "profiles.update_department?department_id={$department_id}";
        } else {
            $url = "profiles.add_department";
        }
        return [CONTROLLER_STATUS_REDIRECT, $url];

    } elseif ($mode == 'delete_department') {
            $department_id = !empty($_REQUEST["department_id"]) ? $_REQUEST['department_id'] : 0;
            fn_delete_department($department_id);
            $url = "profiles.manage_department";
            return [CONTROLLER_STATUS_REDIRECT, $url];

    } elseif ($mode == 'delete_departments') {
        if(!empty($_REQUEST['departments_ids'])) {
            foreach($_REQUEST['departments_ids'] as $department_id) {
                fn_delete_department($department_id);
            }
        }
        $url = "profiles.manage_department";
        return [CONTROLLER_STATUS_REDIRECT, $url];
    }
        
} if ($mode == 'add_department' || $mode == 'update_department') {

    $department_id = !empty($_REQUEST['department_id']) ? $_REQUEST['department_id'] : 0;
    $department_data = fn_get_department_data($department_id, DESCR_SL);

    if (empty($department_data) && $mode == 'update') {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    Tygh::$app['view']->assign([
        'department_data' => $department_data,
        'u_info' => !empty($department_data['user_id']) ? fn_get_user_short_info($department_data['user_id']) : [],
    ]);

} elseif ($mode == 'manage_department') {
    list($departments, $search) = fn_get_departments($_REQUEST, Registry::get('settings.Appearance.admin_elements_per_page'), DESCR_SL);
    Tygh::$app['view']->assign('departments', $departments);
    Tygh::$app['view']->assign('search', $search);
}