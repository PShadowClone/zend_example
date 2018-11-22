<?php


$router->addRoute('contactus', new Zend_Controller_Router_Route(
    'contactus',
    array(
        'module' => 'contactus',
        'controller' => 'Index',
        'action' => 'index'
    )
));

//
$router->addRoute('auth/register', new Zend_Controller_Router_Route(
    'auth/register',
    array(
        'module' => 'Auth',
        'controller' => 'Register',
        'action' => 'index'
    )
));


$router->addRoute('auth/login', new Zend_Controller_Router_Route(
    'auth/login',
    array(
        'module' => 'Auth',
        'controller' => 'Auth',
        'action' => 'index'
    )
));

$router->addRoute('auth/profile', new Zend_Controller_Router_Route(
    'auth/profile',
    array(
        'module' => 'Auth',
        'controller' => 'Profile',
        'action' => 'index'
    )
));

$router->addRoute('auth/profile/delete', new Zend_Controller_Router_Route(
    'auth/profile/delete',
    array(
        'module' => 'Auth',
        'controller' => 'Profile',
        'action' => 'delete'
    )
));

$router->addRoute('auth/logout', new Zend_Controller_Router_Route(
    'auth/logout',
    array(
        'module' => 'Auth',
        'controller' => 'Auth',
        'action' => 'logout'
    )
));

$router->addRoute('users', new Zend_Controller_Router_Route_Regex(
    'users',
    array(
        'module' => 'Auth',
        'controller' => 'Service',
        'action' => 'index'
    )
));

$router->addRoute('user', new Zend_Controller_Router_Route_Regex(
    'users/(\d+)',
    array(
        'module' => 'Auth',
        'controller' => 'Service',
        'action' => 'show'
    )
));

$router->addRoute('user_create', new Zend_Controller_Router_Route_Regex(
    'users/create',
    array(
        'module' => 'Auth',
        'controller' => 'Service',
        'action' => 'create'
    )
));


$router->addRoute('user_update', new Zend_Controller_Router_Route_Regex(
    'users/update/(\d+)',
    array(
        'module' => 'Auth',
        'controller' => 'Service',
        'action' => 'update'
    )
));


$router->addRoute('user_delete', new Zend_Controller_Router_Route_Regex(
    'users/delete/(\d+)',
    array(
        'module' => 'Auth',
        'controller' => 'Service',
        'action' => 'delete'
    )
));

$router->addRoute('lang_update', new Zend_Controller_Router_Route(
    'lang/:lang',
    array(
        'module' => 'Lang',
        'controller' => 'Lang',
        'action' => 'index'
    )
));