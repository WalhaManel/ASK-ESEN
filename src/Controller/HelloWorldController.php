<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HelloWorldController{

    public function sayHello(Request $request): Response{
        $name = $request->query->get("name", "World");
        return new Response("<h1>Hello $name</h1>", 404);
    }

}
