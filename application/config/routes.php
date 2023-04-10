<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'Dashboard';

// routes configuration for custom navigation
$route['packet'] = 'Dashboard/packet_menu';
$route['company'] = 'Dashboard/company_menu';
$route['packet_form'] = 'Dashboard/packet_form';
$route['invoice_form'] = 'Dashboard/invoice_form';
// $route['invoice_form'] = 'Dashboard/invoice_form_new';


$route['home'] = 'Dashboard';
$route['signIn'] = 'Home';
$route['invoice'] = 'Home/print';
// $route['invoice'] = 'Home/print_new';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


?>