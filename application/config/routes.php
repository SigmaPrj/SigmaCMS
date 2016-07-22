<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


/*
| -------------------------------------------------------------------------
| Application Admin Routes
| -------------------------------------------------------------------------
*/
$route['default_controller'] = 'Index';
$route['Callback/(:any)/(:any)/(:num)'] = 'Callback/handler/$1/$2/$3';


/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; //

// API for city
$route['api/city'] = 'api/City/cities';
$route['api/city/(:num)'] = 'api/city/cities/id/$1';

// API for school
$route['api/school'] = 'api/School/schools'; // 获取所有学校
$route['api/school/(:num)'] = 'api/school/schools/id/$1'; // 获取特定学校
// api/school/schools?code=$code 获取城市下所有学校  ?limit=3 获取前三条数据

// API for userType
$route['api/usertype'] = 'api/UserType/userTypes';
$route['api/usertype/(:num)'] = 'api/UserType/userTypes/id/$1';

/**
 * 动态,社区 话题
 */
$route['api/topic'] = 'api/Topic/topics'; // 获取所有话题
$route['api/topic/(:num)'] = 'api/Topic/topics/id/$1';
$route['api/topic/(:num)/(:any)'] = 'api/Topic/topics/id/$1/type/$2'; // 获取特定话题下所有特定内容

$route['api/experience'] = 'api/Experience/experiences'; // 获取所有经验分享
$route['api/experience/(:num)'] = 'api/Experience/experiences/id/$1';
$route['api/experience/(:num)/(:any)'] = 'api/Experience/experiences/id/$1/type/$2';

/**
 * 动态,社区API设计
 */
$route['api/dynamic'] = 'api/Dynamic/dynamics';
$route['api/dynamic/(:num)'] = 'api/Dynamic/dynamics/id/$1';
$route['api/dynamic/(:num)/(:any)'] = 'api/Dynamic/dynamics/id/$1/type/$2';

/**
 * 用户
 */
$route['api/user'] = 'api/User/users'; // limit
$route['api/user/(:num)'] = 'api/User/users/id/$1'; // type 选择 数据返回 类型 brief basic all
$route['api/user/(:num)/(:any)'] = 'api/User/users/id/$1/type/$2'; // 获取用户i的某方面相关数据
$route['api/user/(:num)/(:any)/(:num)'] = 'api/User/users/id/$1/type/$2/type_id/$3'; // 获取,修改等用户 某方面的具体某个数据内容

/**
 * 朋友
 */
$route['api/friend'] = 'api/Friend/friends'; // 获取所有朋友关系数据, 用不到
$route['api/friend/(:num)'] = 'api/Friend/friends/user_id/$1'; // 获取某个人的所有好友
$route['api/friend/(:num)/(:any)'] = 'api/Friend/friends/user_id/$1/type/$2'; // 获取该用户所有好友的某些信息
