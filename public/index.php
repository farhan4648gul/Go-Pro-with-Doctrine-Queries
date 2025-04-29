<?php
// xdebug_disable(); 
// ini_set('error_reporting', false); 
// ini_set('display_errors', false); 
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
