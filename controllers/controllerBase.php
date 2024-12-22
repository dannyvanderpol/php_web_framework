<?php namespace framework;

// Default controller, all controllers must be derived from this
class ControllerBase
{
    public function showDefaultPage($parameters)
    {
        $view = $this->createView(FRAMEWORK_FOLDER . "views/viewDefaultPage.php");
        return $view->generateOutput();
    }

    protected function createView($viewName)
    {
        $view = new ViewPage();
        $view->pageTitle = "Framework default page";
        $view->pageFile = $viewName;
        return $view;
    }
}
