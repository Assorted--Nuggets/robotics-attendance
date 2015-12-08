<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'users';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['register'] = 'users/create_user';
$route['authenticate'] = 'users/auth_clock';
$route['admin'] = 'users/auth_admin';
$route['adminpage'] = 'users/view';
$route['logout'] = 'users/logout';
