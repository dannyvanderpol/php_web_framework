<?php namespace framework;

/* Model for handling sessions. */

class ModelSession
{
    public static function setData($key, $data)
    {
        $_SESSION[$key] = $data;
    }

    public static function getData($key, $default=null)
    {
        return arrayGet($_SESSION, $key, $default);
    }

    public static function clearData($key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroySession()
    {
        // Clear all cookies for this session
        if (ini_get("session.use_cookies"))
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        session_start();
    }
}

?>
