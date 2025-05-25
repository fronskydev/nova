<?php

return [
    "/" => [
        "method" => ["GET", "POST"],
        "action" => "HomeController@index"
    ],
    "/home" => [
        "method" => "GET",
        "action" => "HomeController@index",
        "middleware" => ["HomeMiddleware"]
    ],
];
