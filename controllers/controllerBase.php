<?php namespace framework;

// Default controller, all controllers must be derived from this
class ControllerBase
{
    public function executeAction($action, $level, $parameters)
    {
        return $this->$action($parameters);
    }

    public function createView($viewName)
    {
        $view = new ViewPage();
        $view->pageTitle = "Framework default page";
        $view->pageFile = $viewName;
        return $view;
    }

    private function showDefaultPage($parameters)
    {
        $view = $this->createView("viewDefaultPage.php");
        return $view->generateOutput();
    }
}
