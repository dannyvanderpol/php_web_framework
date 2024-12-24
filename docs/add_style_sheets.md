# Adding style sheets

It is very common to use style sheets (CSS).
They can easily be added to the view.

## Add local style sheets

Let's say you have a number of styles sheets in your application folder.
You can add them as follows:

``` PHP
<?php use framework as F;

class Controller extends F\ControllerBase
{
    public function showHome()
    {
        $view = $this->createView("viewHome.php");
        $view->pageTitle = "My home page";

        // styleSheets is an array of strings, just add a new element to the array
        $view->styleSheets[] = "application/mystyles.css";

        // Or add many style sheets at once.
        $view->styleSheets = [
            "application/reset.css",
            "application/mystyles.css",
            "application/colortheme.css"
        ]

        return $view->generateOutput();
    }
}
