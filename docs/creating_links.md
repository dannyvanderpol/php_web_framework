# Creating links

How best to create links when using the framework.

## Outline

Let's first have a look on how this framework works and why this can become a problem.
The URIs are parsed by the framework to call functions. Let's say you have two URIs:

* page-one
* page-two

Let's say you work from localhost, then in your browser you can go to three web pages:

* http://localhost
* http://localhost/page-one
* http://localhost/page-two

When you are on the home page you can easily link to the other pages like this:

``` HTML
<a href="page-one">link to page one</a>
<a href="page-two">link to page two</a>
```

This will work, because you are on `http://localhost`, the links are relative so the link goes to
`http://localhost/page-one` for the first page, and the second page `http://localhost/page-two`.

Now comes the trick if you want to link from page one to page two.

``` HTML
<a href="page-two">link to page two</a>
```

Using this link will not work. The links are relative. You are on page one.
So your location is `http://localhost/page-one`. Because the link is relative, the link will go to
`http://localhost/page-one/page-two`. Which is not the correct URI.

To prevent this from happening you can use absolute links by placing a '/' in front of the link.

``` HTML
<a href="/page-two">link to page two</a>
```

Then the links always goes to `http://localhost/page-two`.

Problem solved you may think. Unfortunately this only works if your project is in the root of the web server.
For development you may want to use your web server for multiple projects and put each project in a sub folder.

## Using the `LINK_ROOT` define

The issue can be solved using the `LINK_ROOT` define.

If your project is in a subfolder like `my_project` the web pages will have the following locations:

* http://localhost/my_project
* http://localhost/my_project/page-one
* http://localhost/my_project/page-two

An absolute link will not work because that will go to `http://localhost/page-one`.
You can put `/my_project` in front of it to solve that, but if you decide to publish your project to
the root of a production server, all links will stop working. Also when you rename the project folder
the same happens.

For this you can use the `LINK_ROOT` define. The framework wil determine what the root is of your project
and creates a define called `LINK_ROOT`. This can be used as follows:

``` HTML
<a href="{LINK_ROOT}page-two">link to page two</a>
```

The `LINK_ROOT` will be replaced by the location where your project is located:

``` HTML
<a href="http://localhost/my_project/page-two">link to page two</a>
```

The define includes whether you use https and the port number if applicable.

This way you are ensured all links keep working when renaming the project folder or moving the project to another server.

This can also be used for images and other elements that require an URI:

``` HTML
<img src="{LINK_ROOT}images/my-photo.png" />
```
