<?php namespace framework;

// Handles the URI request and calls the user defined controller and action
class ControllerFramework
{
    public static function getResponse()
    {
        $result = self::parseRequestUri();
        $controller = new $result[0]();
        return $controller->{$result[1]}($result[2]);
    }

    private static function parseRequestUri()
    {
        // If URI cannot be parsed, show the default page, with no parameters
        $result = ["framework\\ControllerBase", "showDefaultPage", []];

        return $result;
    }
}
