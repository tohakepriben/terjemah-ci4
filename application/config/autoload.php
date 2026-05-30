<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$autoload['packages'] = array();
$autoload['libraries'] = array('database', 'session');
$autoload['drivers'] = array();
$autoload['helper'] = array(
						'url',
						'fungsi_helper'
					);
$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array(
						'm_main',
						'm_user'
					);
