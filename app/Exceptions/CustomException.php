<?php
 
namespace App\Exceptions;
 
use Exception;
 
class CustomException extends Exception
{
    /** 
* Report the exception. 
* 
* @return void 
*/
protected $dontReport = [
    CustomException::class,
];
    public function report()
    {
    }

 
    /** 
* Render the exception into an HTTP response. 
* 
* @param \Illuminate\Http\Request 
* @return \Illuminate\Http\Response 
*/
    public function render($request)
    {
        return response()->view('errors.custom', array('exception' => $this));
    //    $exception = array('exception' => $this);
    //     dd($exception);
    //     return response()->view('errors.custom',compact('exception'));
    // }
}
}
