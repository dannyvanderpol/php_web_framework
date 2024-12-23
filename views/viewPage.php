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


    protected function getContentFromPageFile($pageFile)
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
        ob_start();
        include $filename;
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
            $output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . LINK_ROOT . "{$styleSheet}\" />\n";
        }
        $output .= "<title>{$this->pageTitle}</title>\n";
        $output .= "</head>\n";
        $output .= $this->generateBody();
        $output .= "</html>\n";
        return $output;
    }

}
