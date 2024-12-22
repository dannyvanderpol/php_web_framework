<?php namespace framework;

// Auto loader for automatically including source files
function autoloader($className)
{
    $searchPaths = [];
    if (str_starts_with($className, __NAMESPACE__ . "\\"));
    {
        $searchPaths[] = FRAMEWORK_FOLDER;
        $className = substr($className, strlen(__NAMESPACE__) + 1);
    }
    // Search paths recursively for a file matching with the class name
    foreach ($searchPaths as $folder)
    {
        if (is_dir($folder))
        {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder)) as $file)
            {
                if (strtolower(basename($file)) == strtolower("{$className}.php"))
                {
                    require_once($file);
                    break;
                }
            }
        }
    }
}
