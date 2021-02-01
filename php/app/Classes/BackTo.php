<?php
namespace Classes;

class BackTo
{
    public static function home()
    {
        header('Location:/');
        exit();
    }

    public static function post($message)
    {
        header('Location:/post.php?message=' . $message);
        exit();
    }
    
    public static function indexMsg($message)
    {
        header('Location:/index.php?message=' . $message);
        exit();
    }

    public static function eachPost($post_id, $message)
    {
        $params = [
            'post_id' => $post_id,
            'message' => $message
        ];
        header('Location:/each_post.php?' . http_build_query($params));
        exit();
    }

    public static function registerMsg($message)
    {
        header('Location:/pages/manager/manager_register.php?message=' . $message);
        exit();
    }

    public static function loginMsg($message)
    {
        // header('Location:/index.php=' . $message);
        header('Location:/pages/manager/manager_login.php?message=' . $message);
        exit();
    }
}