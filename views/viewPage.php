<?php namespace framework;

// Page view class, derive your own page view from this class
class ViewPage
{
    public $docType = "html";
    public $pageTitle = "";
    public $metaElements = [
        "charset=\"UTF-8\"",
        "name=\"viewport\" content=\"width=device-width,initial-scale=1.0\""
    ];
    public $pageFile = "";


    protected function getContentFromPageFile($filename)
    {
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
        $output .= "<title>{$this->pageTitle}</title>\n";
        $output .= "</head>\n";
        $output .= $this->generateBody();
        $output .= "</html>\n";
        return $output;
    }
}
