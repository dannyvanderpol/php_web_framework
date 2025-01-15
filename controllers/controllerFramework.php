<?php namespace framework;

// Handles the URI request and calls the user defined controller and action
class ControllerFramework
{
    public static function getResponse()
    {
        self::forceSSL();
        $result = self::parseRequestUri();
        $controller = new $result[0]();
        return $controller->executeAction($result[1], $result[2], $result[3]);
    }

    private static function parseRequestUri()
    {
        // If URI cannot be parsed, show the default page, with no parameters
        $result = ["framework\\ControllerBase", "showDefaultPage", null, []];
        if (defined("ROUTES"))
        {
            // Check for default route
            $uris = array_column(ROUTES, 0);
            $index = array_search("DEFAULT", $uris);
            if ($index === false)
            {
                throw new \Exception("No default route found.");
            }
            // Set route to default route
            $route = ROUTES[$index];
            $parameters = [];
            // Parse the URI
            // URI can have URI parameters E.G: /my/uri?param=value
            $uriParts = explode("?", REQUEST_URI);
            $requestUri = $uriParts[0];
            FRAMEWORK_LOG->writeMessage("Parse URI: '{$requestUri}'");
            $splittedRequestUri = explode("/", trim($requestUri, "/"));
            foreach ($uris as $index => $uri)
            {
                $params = [];
                // Route must have three parts
                if (count(ROUTES[$index]) < 3)
                {
                    continue;
                }
                $splittedRoute = explode("/", $uri);
                if (count($splittedRequestUri) == count($splittedRoute))
                {
                    // Possible match
                    $matchCount = 0;
                    foreach ($splittedRoute as $i => $part)
                    {
                        if (str_starts_with($part, "{") and str_ends_with($part, "}"))
                        {
                            // Parameter value
                            $params[rawurldecode(substr($part, 1, -1))] = rawurldecode($splittedRequestUri[$i]);
                        }
                        elseif ($part != $splittedRequestUri[$i])
                        {
                            break;
                        }
                        $matchCount++;
                    }
                    if ($matchCount == count($splittedRoute))
                    {
                        // It is a match
                        $route = ROUTES[$index];
                        // Add remaining URI parameters to the parameters, if there were any
                        if (count($uriParts) > 1) {
                            parse_str($uriParts[1], $temp);
                            $params = array_merge($params, $temp);
                        }
                        $parameters = $params;
                        break;
                    }
                }
            }
            FRAMEWORK_LOG->writeMessage("Using route:");
            FRAMEWORK_LOG->writeDataArray($route);
            FRAMEWORK_LOG->writeMessage("Parameters:");
            FRAMEWORK_LOG->writeDataArray($parameters);
            $result[0] = $route[1];
            $result[1] = $route[2];
            $result[2] = arrayGet($route, 3);
            $result[3] = $parameters;
        }
        return $result;
    }

    private static function forceSSL()
    {
        // TODO: handle force SLL properly

        // Force SSL if a domain is setup and force SLL is set in the application initialization script
        // Set in the application initialize script:
        // define("DOMAIN", "<domain_name>");
        // define("FORCE_SSL", true);
        // TODO: defined("DOMAIN") and SERVER_NAME == DOMAIN and
        if (defined("FORCE_SSL") and FORCE_SSL and LINK_ROOT != LINK_ROOT_SSL)
        {
            $currentUri = REQUEST_URI;
            FRAMEWORK_LOG->writeMessage("Forcing SSL for URI: '{$currentUri}'");
            $newUri = LINK_ROOT_SSL . $currentUri;
            FRAMEWORK_LOG->writeMessage("Redirect to: '{$newUri}'");
            header("Location: {$newUri}");
            exit();
        }
        else
        {
            FRAMEWORK_LOG->writeMessage("No force SSL enabled");
        }
    }
}
