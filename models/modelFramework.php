<?php namespace framework;

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

// Show content of one or more variables
function debug(...$variables)
{
    echo "<pre>\n";
    foreach ($variables as $variable)
    {
        echo var_export($variable, true) . "\n";
    }
    echo "</pre>\n";
}
