<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function(Exception $e, $request) {
            // return $this->handleException($request, $e);
        });
    }
    
    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            $errors=$exception->validator->errors()->getMessages();

            return response()->json([
                'ok' => false,
                'message' => $errors
            ],422);  
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelo=strtolower(class_basename($exception->getModel()));
            return response()->json([
                'ok' => false,
                'message' => "No existe ninguna instancia de {$modelo} con el id especificado"
            ],404);  
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'ok' => false,
                'message' => "No autenticado"
            ],401);  
        }

        // if ($exception instanceof AuthorizationException) {
        //     return $this->errorResponse('No posee permisos para ejecutar esta accion',403);
        // }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'ok' => false,
                'message' => "No se encontro la URL especificada {$request->path()}"
            ],404);  
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'ok' => false,
                'message' => "El metodo especificado en la peticion no es valido"
            ],405);
        }

        if ($exception instanceof HttpException) {
            return response()->json([
                'ok' => false,
                'message' => $exception->getMessage()
            ],$exception->getStatusCode());
        }

        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                'ok' => false,
                'message' => "Usuario no autenticado"
            ],401);
        }

        // if ($exception instanceof QueryException) {
        //     $codigo=$exception->errorInfo[1];
        //     if ($codigo==1451) {
        //         return $this->errorResponse('No se puede eliminar de forma permanente el recurso por que esta'.
        //          'realcionado con otro',409);
        //     }
        // }

        return response()->json([
            'ok' => false,
            'message' => "Falla inesperada, intente mas tarde"
        ],500);
    }
}
