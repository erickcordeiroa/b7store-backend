<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\CreateAddressUseCase;
use App\Application\UseCases\GetUserAddressesUseCase;
use App\Infrastructure\Http\Requests\CreateAddressRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AddressesController extends Controller
{
    public function __construct(
        private readonly GetUserAddressesUseCase $getUserAddressesUseCase,
        private readonly CreateAddressUseCase $createAddressUseCase
    ) {
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $addresses = $this->getUserAddressesUseCase->execute($user->id);

        return response()->json([
            'error' => null,
            'addresses' => $addresses->map(fn ($address) => $address->toArray())->toArray(),
        ]);
    }

    public function store(CreateAddressRequest $request): JsonResponse
    {
        $user = Auth::user();
        $addressDTO = $this->createAddressUseCase->execute($request->validated(), $user->id);

        return response()->json([
            'error' => null,
            'address' => $addressDTO->toArray(),
        ], 201);
    }
}
