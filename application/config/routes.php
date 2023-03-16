<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'Dashboard';

// routes configuration for custom navigation
$route['packet'] = 'Dashboard/packet_menu';
$route['company'] = 'Dashboard/company_menu';
$route['packet_form'] = 'Dashboard/packet_form';


$route['home'] = 'Dashboard';
$route['signIn'] = 'Home';
$route['invoice'] = 'Home/print';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


?>