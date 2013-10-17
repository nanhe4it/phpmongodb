<?php

class View {

    protected static $content = 'Hello Word';
    protected static $message;

    public static function setContent($content) {

        self::$content = $content;
    }

    public static function getContent() {
        return self::$content;
    }

    public static function setMessage($message) {
        self::$message = $message;
    }

    public static function getMessage() {
        return self::$message;
    }

}