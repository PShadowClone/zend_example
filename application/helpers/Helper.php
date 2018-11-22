<?php

define('BASE_PATH', APPLICATION_PATH . '../');

/**
 *
 * get the configuration and publish it in all application files
 *
 */
if (!function_exists('config')) {
    function Config($attributeName = null)
    {
        $application = parse_ini_file(APPLICATION_PATH . '/configs/application.ini');
        if ($attributeName) {
            return $application[$attributeName];
        }
        $data = json_encode($application);
        return json_decode($data);
    }
}
/**
 *
 * get the base url of the application
 *
 */
if (!function_exists('url')) {
    function url()
    {
        return sprintf(
            "%s://%s:%d/%s/",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['SERVER_PORT'],
            config()->APPLICATION_NAME
        );
    }
}

/**
 *
 * get the bath to assets
 *
 */
if (!function_exists('asset')) {
    function asset($asset = null)
    {
        if (!$asset)
            return url() . '/public';
        return url() . '/public/' . $asset;

    }
}

/**
 *
 * prepare the ajax request
 *
 */
if (!function_exists('ajax_response')) {
    function ajax_response($status, $message = '', $data = [], $error = 0)
    {
        return json_encode(['status' => $status, 'message' => $message, 'data' => $data, 'error' => $error]);
    }
}


/**
 *
 * prepare the api request
 * @restful json api
 */
if (!function_exists('api_response')) {
    function api_response($message = '', $data = [], $error = 0)
    {
        return json_encode(['message' => $message, 'data' => $data, 'error' => $error]);
    }
}

/**
 *
 * check whether request ajax or not
 *
 */
if (!function_exists('ajax')) {
    function ajax()
    {
        $requestHeader = new Zend_Controller_Request_Http();
        $key = $requestHeader->getHeader('Accept');
        $string = $key;
        $array = array("application/json");
        if (0 < count(array_intersect(array_map('strtolower', explode(',', $string)), $array))) {
            return True;
        }
        return false;
    }
}

/**
 *
 * check the language of system
 *
 */
if (!function_exists('language')) {
    function language()
    {
        $translate = new Zend_Translate();
        $data['lang'] = $translate->getLocale();
        $data['dir'] = 'ltr';
        if ($data['lang'] == 'ar') {
            $data['dir'] = 'rtl';
        }
        return json_decode(json_encode($data));
    }
}