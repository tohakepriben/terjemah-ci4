<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultController('Main');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);

$routes->get('/', 'Main::index');

$routes->match(['get', 'post'], 'main', 'Main::index');
$routes->get('main/get_table_size/(:segment)', 'Main::get_table_size/$1');
$routes->get('main/getEmptyPage/(:segment)', 'Main::getEmptyPage/$1');
$routes->get('main/cari/(:segment)', 'Main::cari/$1');
$routes->get('main/baca/(:segment)/(:num)', 'Main::baca/$1/$2');
$routes->get('main/get_ids/(:segment)', 'Main::get_ids/$1');
$routes->get('main/get_toc/(:segment)', 'Main::get_toc/$1');
$routes->get('main/get_kitab/(:segment)', 'Main::get_kitab/$1');
$routes->get('main/cek_update_js/(:segment)', 'Main::cek_update_js/$1');
$routes->get('main/get_kitab_js/(:segment)', 'Main::get_kitab_js/$1');
$routes->get('main/get_kitab_js/(:segment)/(:any)', 'Main::get_kitab_js/$1/$2');
$routes->get('main/cek_update_db/(:segment)', 'Main::cek_update_db/$1');
$routes->get('main/charcode', 'Main::charcode');
$routes->get('main/coba', 'Main::coba');

$routes->match(['get', 'post'], 'admin123', 'Admin123::index');
$routes->get('admin123/logout', 'Admin123::logout');
$routes->get('admin123/get_max_id/(:segment)', 'Admin123::get_max_id/$1');
$routes->post('admin123/add/(:segment)', 'Admin123::add/$1');
$routes->post('admin123/update_nash/(:num)', 'Admin123::update_nash/$1');
$routes->get('admin123/update_noharokat_batch/(:num)/(:num)', 'Admin123::update_noharokat_batch/$1/$2');
$routes->get('admin123/getEmptyPage/(:segment)', 'Admin123::getEmptyPage/$1');
$routes->get('admin123/add_page/(:segment)/(:num)', 'Admin123::add_page/$1/$2');
$routes->get('admin123/rem_page/(:segment)/(:num)', 'Admin123::rem_page/$1/$2');
$routes->get('admin123/rem_page2/(:segment)/(:num)', 'Admin123::rem_page2/$1/$2');
$routes->post('admin123/add_toc/(:segment)', 'Admin123::add_toc/$1');
$routes->post('admin123/update_toc/(:segment)/(:num)', 'Admin123::update_toc/$1/$2');
$routes->get('admin123/rem_toc/(:segment)/(:num)', 'Admin123::rem_toc/$1/$2');
$routes->get('admin123/update_all_toc/(:segment)', 'Admin123::update_all_toc/$1');
$routes->get('admin123/update_db_server/(:segment)', 'Admin123::update_db_server/$1');
$routes->get('admin123/matan_biru', 'Admin123::matan_biru');
$routes->get('admin123/nasafiRemAyat/(:num)/(:num)', 'Admin123::nasafiRemAyat/$1/$2');
$routes->get('admin123/gantiPetikDenganKurung', 'Admin123::gantiPetikDenganKurung');
$routes->get('admin123/updateDB/(:segment)/(:segment)', 'Admin123::updateDB/$1/$2');
$routes->get('admin123/top_words', 'Admin123::top_words');
$routes->get('admin123/tambah_harakat', 'Admin123::tambah_harakat');

$routes->get('ch', 'Ch::index');
$routes->post('ch/insert/(:segment)', 'Ch::insert/$1');

$routes->get('hadits', 'Hadits::index');
$routes->get('hadits/get/(:segment)', 'Hadits::get/$1');
$routes->get('hadits/get_toc/(:segment)', 'Hadits::get_toc/$1');

$routes->get('kmtq', 'Kmtq::index');
$routes->get('kmtq/get_arid', 'Kmtq::get_arid');
$routes->get('kmtq/get_arar', 'Kmtq::get_arar');
$routes->get('kmtq/get_tjalal', 'Kmtq::get_tjalal');

$routes->post('user/login', 'User::login');
$routes->get('user/logout', 'User::logout');
