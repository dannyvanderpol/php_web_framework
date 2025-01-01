<?php namespace framework;

// Log all warnings and errors to the system logger.
function errorHandler($errno, $errstr, $errfile, $errline)
{
    $log = new ModelLogger("errorHandler");
    $log->writeMessage("Errno $errno: $errstr");
    $log->writeMessage("$errfile line $errline");
    // If on localhost, show warnings and errors in the browser.
    if (IS_LOCALHOST)
    {
        // On local host always use the internal PHP handler
        return false;
    }
    // Other hosts, disable the internal PHP handler
    return true;
}

// Auto loader for automatically including source files
function autoloader($className)
{
    $searchPaths = [];
    if (str_starts_with($className, __NAMESPACE__ . "\\"))
    {
        $searchPaths = FRAMEWORK_SEARCH_PATHS;
    }
    else
    {
        $searchPaths = APPLICATION_SEARCH_PATHS;
    }
    // Remove any names spaces
    $parts = explode("\\", $className);
    $className = end($parts);
    // Search paths recursively for a file matching with the class name
    foreach ($searchPaths as $folder)
    {
        if (is_dir($folder))
        {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder)) as $file)
            {
                if (strtolower($file->getBaseName()) == strtolower("{$className}.php"))
                {
                    require_once($file->getRealPath());
                    break;
                }
            }
        }
    }
}

// Gets the protocol
function getProtocol()
{
    $protocol = "http";
    if (arryGet($_SERVER, "HTTPS", "") == "on" or arrayGet($_SERVER["REDIRECT_HTTPS"]) == "on")
    {
        $protocol = "https";
    }
    return $protocol;
}
