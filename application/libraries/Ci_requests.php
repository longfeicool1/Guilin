<?php

include_once APPPATH . '/third_party/Requests/Requests.php';

class Ci_requests
{
    public function __construct()
    {
        Requests::register_autoloader();
    }

}
