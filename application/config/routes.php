<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



$route['login'] = 'C_login/login';
$route['login/aksi'] = 'C_login/aksi';

$route['app/logout'] = 'C_login/logout';


$route['app/dashboard'] = 'app/C_home';


$route['app/akses'] = 'app/C_akses';
$route['app/akses/data'] = 'app/C_akses/data';
$route['app/akses/add'] = 'app/C_akses/add';
$route['app/akses/insert'] = 'app/C_akses/insert';
$route['app/akses/edit/(:any)'] = 'app/C_akses/edit/$1';
$route['app/akses/update/(:any)'] = 'app/C_akses/update/$1';
$route['app/akses/delete/(:any)'] = 'app/C_akses/delete/$1';


$route['app/menu'] = 'app/C_menu';
$route['app/menu/data'] = 'app/C_menu/data';
$route['app/menu/add'] = 'app/C_menu/add';
$route['app/menu/insert'] = 'app/C_menu/insert';
$route['app/menu/edit/(:any)'] = 'app/C_menu/edit/$1';
$route['app/menu/update/(:any)'] = 'app/C_menu/update/$1';
$route['app/menu/delete/(:any)'] = 'app/C_menu/delete/$1';


$route['app/user'] = 'app/C_user';
$route['app/user/data'] = 'app/C_user/data';
$route['app/user/add'] = 'app/C_user/add';
$route['app/user/insert'] = 'app/C_user/insert';
$route['app/user/edit/(:any)'] = 'app/C_user/edit/$1';
$route['app/user/update/(:any)'] = 'app/C_user/update/$1';
$route['app/user/delete/(:any)'] = 'app/C_user/delete/$1';


$route['app/role'] = 'app/C_role';
$route['app/role/data'] = 'app/C_role/data';
$route['app/role/add'] = 'app/C_role/add';
$route['app/role/insert'] = 'app/C_role/insert';
$route['app/role/edit/(:any)'] = 'app/C_role/edit/$1';
$route['app/role/update/(:any)'] = 'app/C_role/update/$1';
$route['app/role/delete/(:any)'] = 'app/C_role/delete/$1';


$route['app/pelaporan'] = 'app/C_pelaporan';
$route['app/pelaporan/data'] = 'app/C_pelaporan/data';
$route['app/pelaporan/add'] = 'app/C_pelaporan/add';
$route['app/pelaporan/insert'] = 'app/C_pelaporan/insert';
$route['app/pelaporan/edit/(:any)'] = 'app/C_pelaporan/edit/$1';
$route['app/pelaporan/detail/(:any)'] = 'app/C_pelaporan/detail/$1';
$route['app/pelaporan/update/(:any)'] = 'app/C_pelaporan/update/$1';
$route['app/pelaporan/delete/(:any)'] = 'app/C_pelaporan/delete/$1';




$route['api/auth']       = 'api/Auth';
$route['api/user']       = 'api/User';
$route['api/menu']       = 'api/Menu';
$route['api/role']       = 'api/Role';
$route['api/roleAkses']  = 'api/RoleAkses';
$route['api/insiden']  = 'api/Insiden';
$route['api/insidenBerkas']  = 'api/InsidenBerkas';