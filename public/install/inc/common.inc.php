<?php

define('REQUIREMENT_PASSED', 1);
define('REQUIREMENT_ERROR', 0);
define('REQUIREMENT_WARNING', -1);

define('DS', DIRECTORY_SEPARATOR);

/**
 * Returns a localized message according to user preferred language.
 * @param string message category
 * @param string message to be translated
 * @param array parameters to be applied to the translated message
 * @return string translated message
 */
function t($category, $message, $params=array())
{
    static $messages;

    if ($messages === null) {
        $messages = array();
        if(($lang=getPreferredLanguage()) !== false) {
            $file = dirname(__FILE__)."/messages/$lang/yii.php";
            if (is_file($file))
                $messages = include($file);
        }
    }

    if (empty($message)) return $message;

    if (isset($messages[$message]) && $messages[$message] !== '')
        $message = $messages[$message];

    return $params !== array() ? strtr($message, $params) : $message;
}

function renderFile($_file, $_params=array())
{
    extract($_params);
    require($_file);
}

function checkRuntimeAccess()
{
    $path = dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..' . DS . 'protected' . DS . 'runtime';
    return file_exists($path) && is_writable($path);
}

function checkDataAccess()
{
    $path = dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..' . DS . 'protected' . DS . 'data';
    return file_exists($path) && is_writable($path);
}

function checkUploadAccess()
{
    $path = dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..' . DS . 'uploads';
    return file_exists($path) && is_writable($path);
}

function checkAssetsAccess()
{
    $path = dirname(__FILE__) . DS . '..' . DS . '..' . DS . '..' . DS . 'resources' . DS . 'assets';
    return file_exists($path) && is_writable($path);
}