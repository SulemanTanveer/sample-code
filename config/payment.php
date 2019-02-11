<?php

return [
    "name" => "lemon-way",
    "wlLogin" => env("WLLOGIN", "xxx"),
    "wlPass" => env("WLPASS", "xxx"),
    "version" => env("VERSION", "xxx"),
    "language" => env("LANGUAGE", "xxx"),
    "wallet" => env("WALLET", "xxx"),
    "redirect_url" => env("PP_REDIRECT_URL", "xxxx"),
    "cancel_url" => env("PP_CANCEL_URL", "xxxx"),
    "failed_url" => env("PP_FAILED_URL", "xxxx"),
    "directkit_json2" => env("DIRECTKITJSON2", "https://ws.lemonway.fr/mb/lwecommerce/prod/lw4e_json/Service_json.asmx"),
    "webKitUrl" => env("WEBKITURL","https://webkit.lemonway.fr/mb/lwecommerce/prod/")
];