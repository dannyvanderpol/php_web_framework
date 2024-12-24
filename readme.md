# PHP web framework

Generic PHP web framework for creating web sites.

* PHP V8+
* MVC pattern (model, view, controller)
* Pretty URIs: http://server/name-of-page
* URIs can have parameters:
  * http://server/show-products/category/tools/order-by/price
* Also support reqular parameters:
  * http://server/show-products?category=tools&order_by=price
* Use as a submodule in your project
* Designed for Apache web servers
* Light weight: 8 PHP files with 339 code lines

## Web server

For this project the Apache web server is used. A PHP module must also be installed to make it work.
If you want to use another web server, refer to the manual of that web server on how to run a PHP web site.


## Setup your project

Start a new empty git project. Clone the framework as a submodule in your project:

```
git submodule add https://github.com/dannyvanderpol/php_web_framework.git framework
```

Instead of `framework` you can also use another name.
Read the git manual about submodules for more information about working with sub modules (https://www.google.com/search?q=git+submodules).

Create a new `index.php` in the root of your project and add the following lines:

```
<?php

include("framework/index.php);
```

Copy the `.htaccess_root` from the framework to the root of your project folder, and rename the file to `.htaccess`.

Now you should have a project like this:

```
my_project
 |- framework/              // the PHP web framework as a submodule
 |- .gitmodules             // configuration file for git about the submodules
 |- .htaccess               // the .htaccess file of your project
 |- index.php               // the index.php file of your project
```

If you open the project in the web browser it should show the 'Framework default page'.
This shows a message that the requested URI is not found.
This is because your application has no URIs defined and there are no controllers and views in your project.


## Create your first page

The minimum required to show your page is one URI, one controller and one view. So let's create those.
In this case we create a folder `application` for all application related stuff.
In the folder application we create four files: `.htaccess`, `controller.php`, `initialize.php` and `viewHome.php`.
Our project now looks like this

```
my_project
 |- application/            // the application folder
 |   |- .htaccess           // managing the access to the application folder
 |   |- controller.php      // the application controller
 |   |- initialize.php      // initialize the application
 |   |- viewHome.php        // the view for the application
 |- framework/              // the PHP web framework as a submodule
 |- .gitmodules             // configuration file for git about the submodules
 |- .htaccess               // the .htaccess file of your project
 |- index.php               // the index.php file of your project
```

If your project grows you may have more controllers, views and even models.
You can always put them in sub folders to organise them in any way your like.
For now we leave it to this to make it not too complicated.

### .htaccess

With the `.htaccess` file you can manage the access to the application folder.
We don't want people to execute `http://server/application/controller.php`.
To prevent this we add the following line to the `.htaccess` file:

```
Deny from all
```

Note this only works for Apache based web servers. If you use another type of web server,
check the manual of that web server on how to manage access.
This applies to all `.htaccess` files used in this project.


### Routes

Routes can be defined in the `initialize.php` file as follows:

```
<?php

define("ROUTES", [
    ["DEFAULT", "Controller", "showHome"]
]);
```

At least one route must be called `DEFAULT`.
This route is used in case the requested URI is not found in the list of routes.
An error will be reported if the default route is missing.
The route must then specify which controller to load and which function of that controller should be called.

To load the routes we add it as a `require_once` in the `index.php`:

```
<?php

require_once("application/initialize.php");

include("framework/index.php");
```

When the page loads, the routes are loaded and the framework can use them.

If you reload your page you will get the error that the class `Controller` is not found.
This is correct because there is no controller yet.


### Views

We prepare the view first, so if it loads we will actually see something in the browser.
Open the file `view.php` and add the following lines:

```
<h1>Welkom to my home page</h1>
<p>This is my homepage shown to you using the PHP web framework.</p>
```

Although it is a PHP file, you can use plain HTML. You can also mix it with PHP.
This file is read and parsed by the framework and the output is put in a HTML page.


### Controllers

Controllers are used to prepare the view base on the request (URI).
In case the requests leads to a static page, there is not much to prepare.
In case the request leads to a dynamic page (for example data from a database), there is more to prepare.
Finally the controller sends the page to the browser.
The framework contains a base controller which should be used to create your own controller.
Open the file controller.php and add the following lines:

```
<?php use framework as F;

class Controller extends F\ControllerBase
{
    public function showHome()
    {
        $view = $this->createView("viewHome.php");
        $view->pageTitle = "My home page";
        return $view->generateOutput();
    }
}
```

The controller extends the base controller from the framework.
A function `showHome` is defined. This is the name of the function as defined in the route.
The function creates a view based on the `viewHome.php` file. This is a function from the base controller.
It returns an instance of the framework `viewPage` class.
We set the page title, this is the value of the `<title>` element in the HTML page.
The we return the generated output which is the actual HTML.

If you reload the page you will still see the same error message.

There is one final thing to be set. The search paths. The framework has an autoloader.
This means if a class is used or a view is loaded, it will load the class or view automatically.
This way you do not need to use `require` or `include` statements to load the correct PHP files.
It is all done automagically.
The only thing you need to define are the folders where the autoloader can search for the PHP files.

Open the `initialize.php` and add the search paths:

```
<?php

define("ROUTES", [
    ["DEFAULT", "Controller", "showHome"]
]);

define("SEARCH_PATHS", ["application"]);
```

The paths are relative to the project folder. We now added the complete application folder.
This means it will search all files and folders recursivly.
If your project grows you may wat to organise and limit the amount of folders to search.
The less folders it needs to search the faster the performance is.
In the next example we organise all controllers and views in separate folders and limit the search:

```
define("SEARCH_PATHS", [
    "application/controllers",
    "application/views
]);
```

Only two folders are defined here. May be your application has more folders for style sheets,
JavaScript, images, etc. Those folders will be skipped.

If you reload your page in the browser it now should show the home page.


## Advanced stuff

Explanation of all features is available in the docs folder.

See: https://github.com/dannyvanderpol/php_web_framework_demo for demo page with more features.
