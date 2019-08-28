<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;

class ExampleController extends Controller
{
    use ApiResponser;

    /**
     * The service to consume the authors microservice
     * @var AuthorService
     */
    public $authorService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Return the list of authors
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse(['hello Lumen']);
    }
}
