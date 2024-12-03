<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\DTO\Input\RegistrationInput;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    public function registration(Request $request): JsonResponse
    {
        try {
            $registrationData = RegistrationInput::fromArray($request->all());

            $this->userService->create($registrationData);
        } catch (ValidationException $exception) {
            return new JsonResponse(
                ['errors' => $exception->errors()],
                Response::HTTP_BAD_REQUEST,
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        return new JsonResponse('Registration success', Response::HTTP_CREATED);
    }
}
