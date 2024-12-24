# Template values

The page view generates output and is able to use template values.

## Using custom template values

Templates values are defined using the `define` statement:

``` PHP
define("{MY_NAME}", "John Doe");
```

In the HTML of your page you can use this as follows:

``` HTML
<p>My name is: {MY_NAME}.</p>
```

Template value names must be encapsulated with braces. The output will be:

```
My name is John Doe.
```

## Using build in template values

The framework has a number of build in template values.
These are all the defines from the `initialize.php` script.
For example:

``` HTML
<p>The framework folder is: '{FRAMEWORK_FOLDER}'</p>
```

Will output:

```
The framework folder is: 'C:/path/to/the/framework/'
```

A handly one is `LINK_ROOT` for creating links:

``` HTML
<p>Click <a href="{LINK_ROOT}about_me">here</a> to read more about me.</p>
```

The `{LINK_ROOT}` will be replaced with the web location of your project.
Let's say your are working on localhost and your project is in a sub folder called 'my_project',
the link will be this:

``` HTML
<p>Click <a href="http://localhost/my_project/about_me">here</a> to read more about me.</p>
```

More info about creating links in the document `creating_links.md`.
