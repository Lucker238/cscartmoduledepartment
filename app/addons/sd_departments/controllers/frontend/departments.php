<?php
use Tygh\Registry;

defined('BOOTSTRAP') or die('Access denied');

if ($mode == 'departments') { 

    Tygh::$app['session']['continue_url'] = "departments.departments";

    $params = $_REQUEST;
    $params['user_id'] = Tygh::$app['session']['auth']['user_id'];
    $user_info = fn_get_user_short_info($params['user_id']);
    list($departments, $search) = fn_get_departments($params, 3, CART_LANGUAGE);

    Tygh::$app['view']->assign('departments', $departments);
    Tygh::$app['view']->assign('search', $search);
    Tygh::$app['view']->assign('columns', 3);
    
    fn_add_breadcrumb(__("sd_departments.departments"));


} elseif ($mode === 'department') {
    $department_data = [];
    $department_id = !empty($_REQUEST['department_id']) ? $_REQUEST['department_id'] : 0;
    $department_data = fn_get_department_data($department_id, CART_LANGUAGE);
    if (empty($department_data)) {
        return [CONTROLLER_STATUS_NO_PAGE];
    }

    Tygh::$app['view']->assign('department_data', $department_data);

    fn_add_breadcrumb(__("sd_departments.departments"), "departments.departments");
    fn_add_breadcrumb($department_data["department"]);


    $params = $_REQUEST;
    $params['extend'] = ['description'];

Tygh::$app['view']->assign('search', $search);
}