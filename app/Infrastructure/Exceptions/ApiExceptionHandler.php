<?php

declare(strict_types=1);

namespace App\Infrastructure\Exceptions;

use App\Application\DTOs\ErrorResponseDTO;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionHandler
{
    public function handle(Throwable $exception, Request $request): JsonResponse
    {
        return match (true) {
            $exception instanceof ValidationException => $this->handleValidationException($exception),
            $exception instanceof ModelNotFoundException => $this->handleModelNotFoundException($exception),
            $exception instanceof NotFoundHttpException => $this->handleNotFoundHttpException($exception),
            $exception instanceof HttpException => $this->handleHttpException($exception),
            default => $this->handleGenericException($exception),
        };
    }

    private function handleValidationException(ValidationException $exception): JsonResponse
    {
        $dto = new ErrorResponseDTO(
            message: 'Os dados fornecidos são inválidos.',
            statusCode: Response::HTTP_UNPROCESSABLE_ENTITY,
            errors: $exception->errors()
        );

        return response()->json(
            $dto->toArray(),
            $dto->statusCode
        );
    }

    private function handleModelNotFoundException(ModelNotFoundException $exception): JsonResponse
    {
        $model = class_basename($exception->getModel());
        $message = "Recurso não encontrado.";

        $dto = new ErrorResponseDTO(
            message: $message,
            statusCode: Response::HTTP_NOT_FOUND
        );

        return response()->json(
            $dto->toArray(),
            $dto->statusCode
        );
    }

    private function handleNotFoundHttpException(NotFoundHttpException $exception): JsonResponse
    {
        $dto = new ErrorResponseDTO(
            message: 'Rota não encontrada.',
            statusCode: Response::HTTP_NOT_FOUND
        );

        return response()->json(
            $dto->toArray(),
            $dto->statusCode
        );
    }

    private function handleHttpException(HttpException $exception): JsonResponse
    {
        $dto = new ErrorResponseDTO(
            message: $exception->getMessage() ?: 'Erro na requisição.',
            statusCode: $exception->getStatusCode()
        );

        return response()->json(
            $dto->toArray(),
            $dto->statusCode
        );
    }

    private function handleGenericException(Throwable $exception): JsonResponse
    {
        $statusCode = method_exists($exception, 'getStatusCode')
            ? $exception->getStatusCode()
            : Response::HTTP_INTERNAL_SERVER_ERROR;

        $message = config('app.debug')
            ? $exception->getMessage()
            : 'Ocorreu um erro interno no servidor.';

        $trace = config('app.debug')
            ? $this->formatTrace($exception)
            : null;

        $dto = new ErrorResponseDTO(
            message: $message ?: 'Ocorreu um erro interno no servidor.',
            statusCode: $statusCode,
            trace: $trace
        );

        return response()->json(
            $dto->toArray(),
            $dto->statusCode
        );
    }

    private function formatTrace(Throwable $exception): string
    {
        return sprintf(
            "%s: %s in %s:%d\n%s",
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
    }
}

