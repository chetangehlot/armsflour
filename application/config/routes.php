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
  |	https://codeigniter.com/user_guide/general/routing.html
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
//$route['default_controller'] = 'frontend/Home';
//$route['default_controller'] = "frontend/Home/commingsoon";
$route['default_controller'] = "frontend/Home";
$route['home'] = 'frontend/Home';
$route['main'] = 'frontend/Home/home';
$route['about'] = 'frontend/Home/about';
$route['partyorder'] = 'frontend/Home/partyorder';
$route['contact'] = 'frontend/Home/contact';
$route['careers'] = 'frontend/Home/careers';
$route['faq'] = 'frontend/Home/FAQ';
$route['offers'] = 'frontend/Home/offers';
$route['login'] = 'frontend/Home/login';

$route['soon'] = 'frontend/Home/commingsoon';

$route['termsCond'] = 'frontend/Home/termsCond';
$route['privacyPolicy'] = 'frontend/Home/privacyPolicy';
$route['deliveryPolicy'] = 'frontend/Home/deliveryPolicy';
$route['returnPolicy'] = 'frontend/Home/returnPolicy';
$route['service'] = 'frontend/Home/service';
$route['product/(:any)'] = 'frontend/Home/product/$1';
$route['product'] = 'frontend/Home/product';

$route['translate_uri_dashes'] = FALSE;

// admin routes
// Setting areas rout
$route["admin"] = "admin/login";
$route["admin/logout"] = "admin/login/logout";
$route["admin/profile"] = "admin/dashboard/profile";
$route["admin/setting"] = "admin/dashboard/setting";


// product 
$route["admin/product/add"] = "admin/product/product/add";
$route["admin/product/add/(:any)"] = "admin/product/product/add/$1";
$route["admin/product"] = "admin/product/product";
$route["admin/product/excel"] = "admin/product/product/excel";
$route["admin/product/del/(:any)"] = "admin/product/product/del/$1";

//Admin category
$route["admin/category/add"] = "admin/category/add";
$route["admin/category/add/(:any)"] = "admin/category/add/$1";
$route["admin/category"] = "admin/category";
$route["admin/category/del/(:any)"] = "admin/category/del/$1";

?>