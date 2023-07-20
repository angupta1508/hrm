<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;

class ExceptionController extends Controller
{
    
    public function index()
    {
        // something went wrong and you want to throw CustomException 
        throw new CustomException();
    }
}
