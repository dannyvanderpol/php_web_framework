# URIs with parameters

You can define parameters in the URIs.

## Adding URIs with parameters to the routes

The URIs with parameters can be added as follows:

``` PHP
define("ROUTES", [
    ["DEFAULT", "Controller", "showHome"],

    // Route with parameters
    // Can have different formats, we must define each format
    // Show the page without parameters
    ["parameters",                                     "Controller", "showParameters"],
    // Only parameter 1
    ["parameters/param-1/{param_1}",                   "Controller", "showParameters"],
    // Only parameter 2
    ["parameters/param-2/{param_2}",                   "Controller", "showParameters"],
    // Both parameters
    ["parameters/param-1/{param_1}/param-2/{param_2}", "Controller", "showParameters"],
    // Swapped order
    ["parameters/param-2/{param_2}/param-1/{param_1}", "Controller", "showParameters"]
]);
```

In this example the URI can have two parameters in a number of different ways.
All URIs point to the same page. In the controller the function showParameters is called.

``` PHP
class ControllerPages extends ControllerApplication
{
    protected function showHome()
    {
        $view = $this->createView("viewHome.php");
        return $view->generateOutput();
    }

    protected function showParameters($parameters)
    {
        $view = $this->createView("viewParameters.php");
        $view->pageData = $parameters;
        return $view->generateOutput();
    }
}
```

The parameters with their values are passed in an array to the function in a variable `$parameters`.
The parameters van be used in the function. In this case we pass the parameters directly to the view
using the `pageData` property. Then in the view we can access the parameters.
The view `viewParameters.php` can look like this:

``` PHP
<p>This page shows the values of the parameters in the URI:</p>

<pre>
<?php

// We can use the keyword $this to access the properties of the page class
var_export($this->pageData);

?>
</pre>
```

For the URI `parameters/param-1/1234/param-2/5678`, the output will look like this:

```
This page shows the values of the parameters in the URI:

array (
  'param_1' => '1234',
  'param_2' => '5678'
)
```

Each parameter is a key value pair. If a parameter is omitted, it is also not in the array.
This can be checked using `isset($parameters["param_1"])`.

Parameters can also be added in a traditional way.

For the URI `parameters/param-1/1234/param-2/5678?extra_1=abcd&extra_2=efgh`, the output will look like this:

```
This page shows the values of the parameters in the URI:

array (
  'param_1' => '1234',
  'param_2' => '5678',
  'extra_1' => 'abcd',
  'extra_2' => 'efgh',
)
```

Becarefull when creating URIs with parameters they can easily be come the same:

``` PHP
define("ROUTES", [
    ["DEFAULT",               "Controller", "showHome"      ],

    ["show-page/{page_name}", "Controller", "showCustomPage"],
    ["show-page/about-us",    "Controller", "showAboutUs"   ]
]);
```

If we navigate to URI `show-page/product` it will match the first URI.
If we navigate to URI `show-page/about-us` it will also match the first URI.

The second URI will never be used.

To fix this you can change the order:

``` PHP
define("ROUTES", [
    ["DEFAULT",               "Controller", "showHome"      ],

    ["show-page/about-us",    "Controller", "showAboutUs"   ],
    ["show-page/{page_name}", "Controller", "showCustomPage"]
]);
```

The URIs are parsed from top to bottom, then it will work.
