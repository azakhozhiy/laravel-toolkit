<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Exception;

use AZakhozhiy\Laravel\Exceptions\BaseServiceException;
use AZakhozhiy\Laravel\Exceptions\Service\ExceptionRepository;
use AZakhozhiy\Laravel\Toolkit\Exception\Request\Codes\RequestValidationError;
use AZakhozhiy\Laravel\Toolkit\Exception\Request\ExceptionCategory as RequestCategoryException;
use AZakhozhiy\Laravel\Toolkit\Exception\System\Codes\SystemUnknownError;
use AZakhozhiy\Laravel\Toolkit\Exception\System\ExceptionCategory as SystemCategoryException;
use AZakhozhiy\LogUtils\SmartLogger\SmartLogger;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as LaravelExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandler extends LaravelExceptionHandler
{
    protected SmartLogger $logger;
    protected ExceptionRepository $exceptionRepository;

    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [];
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [];

    /**
     * @throws BindingResolutionException
     */
    public function __construct(Container $container)
    {
        parent::__construct($container);
        $this->exceptionRepository = $container->make(ExceptionRepository::class);
        $this->logger = SmartLogger::init(
            '<HANDLER-EXCEPTION>',
            $container->make(LoggerInterface::class)
        );
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            $this->setToLogger($e);

            if ($e instanceof ValidationException) {
                $this->logger->error(
                    "Validation error, message: {$e->getMessage()}.",
                    $e->validator->errors()->all()
                );

                return $this->validationErrorResponse($e);
            }

            if ($e instanceof BaseServiceException) {
                $errorMsg = $e->getExceptionObject()->getErrorMessage();
                $context = [];

                if (is_array($errorMsg)) {
                    $context = $errorMsg;
                }

                $this->logger->error("Service error, message: {$e->getMessage()}.", $context);

                return $this->serviceErrorResponse($e);
            }

            return $this->unknownErrorResponse($e);
        });
    }

    protected function setToLogger(Throwable $e): void
    {
        $this->logger->setPrefix($e::class)->error(
            $e->getMessage(),
            app()->environment() !== 'production ' ? [
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ] : []
        );
    }

    protected function validationErrorResponse(ValidationException $e): JsonResponse
    {
        $error = $this->exceptionRepository->buildException(
            RequestCategoryException::getSlug(),
            RequestValidationError::getErrorCode(),
            $e->validator->errors()->toArray(),
            null,
            $e
        );

        return response()->json($error->toArray(), $error->getHttpCode());
    }

    protected function serviceErrorResponse(BaseServiceException $e): JsonResponse
    {
        return response()->json($e->toArray(), $e->getHttpCode());
    }

    protected function unknownErrorResponse(Throwable $e): JsonResponse
    {
        $errorMsg = app()->isProduction()
            ? SystemUnknownError::getErrorMessage()
            : $e->getMessage();

        $error = $this->exceptionRepository->buildException(
            SystemCategoryException::getSlug(),
            SystemUnknownError::getErrorCode(),
            $errorMsg
        );

        return response()->json($error->toArray(), $error->getHttpCode());
    }
}
