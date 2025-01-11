<?php namespace framework;

// Page view class, derive your own page view from this class
class ViewPage
{
    public $docType = "html";
    public $metaElements = [
        "charset=\"UTF-8\"",
        "name=\"viewport\" content=\"width=device-width,initial-scale=1.0\""
    ];
    public $pageTitle = "";
    public $pageFile = "";
    public $styleSheets = [];
    public $javaScriptFiles = [];
    public $javaScriptVariables = [];
    public $pageData = null;


    protected function getContentFromPageFile($pageFile, $variables=[])
    {
        // Search application first, then framework
        $searchPaths = array_merge(APPLICATION_SEARCH_PATHS, FRAMEWORK_SEARCH_PATHS);
        $filename = "page file '{$pageFile}' not found";
        foreach ($searchPaths as $folder)
        {
            if (is_dir($folder))
            {
                foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder)) as $file)
                {
                    if (strtolower($file->getBaseName()) == strtolower($pageFile))
                    {
                        $filename = $file->getRealPath();
                        break;
                    }
                }
            }
        }
        if (count($variables) > 0)
        {
            extract($variables);
        }
        ob_start();
        include "{$filename}";
        return ob_get_clean();
    }

    public function generateBody()
    {
        $output = "<body>\n";
        $output .= $this->getContentFromPageFile($this->pageFile);
        $output .= "</body>\n";
        return $output;
    }

    public function generateOutput()
    {
        $output = "<!DOCTYPE {$this->docType}>\n";
        $output .= "<html>\n";
        $output .= "<head>\n";
        foreach ($this->metaElements as $element)
        {
            $output .= "<meta {$element}>\n";
        }
        foreach ($this->styleSheets as $styleSheet)
        {
            // Add timestamp to prevent browers caching
            $mtime = filemtime(SERVER_ROOT . WEB_FOLDER . $styleSheet);
            $output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . LINK_ROOT . "{$styleSheet}?{$mtime}\" />\n";
        }
        if (count($this->javaScriptVariables) > 0)
        {
            $output .= "<script> // Variables from the framework\n";
            $output .= "'use strict';\n";
            foreach ($this->javaScriptVariables as $name => $value)
            {
                $output .= "const {$name} = {$value};\n";
            }
            $output .= "</script>\n";
        }
        foreach ($this->javaScriptFiles as $javaScriptFile)
        {
            // Add timestamp to prevent browers caching
            $mtime = filemtime(SERVER_ROOT . WEB_FOLDER . $javaScriptFile);
            $output .= "<script src=\"" . LINK_ROOT . "{$javaScriptFile}?{$mtime}\"></script>\n";
        }
        $output .= "<title>{$this->pageTitle}</title>\n";
        $output .= "</head>\n";
        $output .= $this->generateBody();
        $output .= "</html>\n";
        return $this->replaceTemplateValues($output);
    }

    private function replaceTemplateValues($input)
    {
        // Replace template values from user defined constants
        // define("{MY_CONSTANT}", "my value");
        // some text with a {MY_CONSTANT} will be replaced with 'my value';
        $templateValues = [];
        foreach (get_defined_constants(true)["user"] as $key => $value)
        {
            // Only use string values
            if (is_string($value))
            {
                $templateValues["{{$key}}"] = $value;
            }
        }
        return strtr($input, $templateValues);
    }
}
