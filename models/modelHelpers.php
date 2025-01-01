<?php namespace framework;

// Split lines into an array using various variants of new line characters
function splitLines($value)
{
    return preg_split("/\r\n|\n|\r/", $value);
}

// Show content of one or more variables
function debug(...$variables)
{
    // We use var_dump because it can handle recursion, but we need to use the output buffer
    ob_start();
    var_dump(...$variables);
    $output = ob_get_clean();
    echo "<pre>\n";
    echo formatVarDump($output);
    echo "</pre>\n";
}

// Get a value from an array with key value pairs, return default value if the key does not exist
function arrayGet($array, $key, $default=null)
{
    return (isset($array[$key]) ? $array[$key] : $default);
}

// Format var_dump output so it looks nicer
function formatVarDump($input)
{
    $input = str_replace("]=>\n", "]=>", $input);
    return preg_replace("/\]=>\s+/", "] => ", $input);
}

// Retriev posted data, JSON formatted or from $_POST
function getPostedData()
{
    if (count($_POST) > 0)
    {
        // Regular posted data
        return $_POST;
    }
    $body = file_get_contents("php://input");
    $jsonData = json_decode($body, true);
    if ($jsonData != null)
    {
        // Body contains valid JSON formatted data
        return $jsonData;
    }
    // Just return the body (could be a format we do not know, or incorrectly formatted data)
    return $body;
}
