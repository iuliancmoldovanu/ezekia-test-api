<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\CurrencyConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private CurrencyConverter $currencyConverter;

    public function __construct(CurrencyConverter $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = User::create($request->only(['name', 'hourly_rate', 'currency']));

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, User $user): JsonResponse
    {
        $targetCurrency = strtoupper($request->query('currency', null));
        if( !empty($targetCurrency) ){

            if (!in_array($targetCurrency, ['EUR', 'USD', 'GBP'])) {
                return response()->json(['error' => 'Invalid target currency'], 400);
            }

            $convertedHourlyRate = $this->currencyConverter->convert($user->hourly_rate, $user->currency, $targetCurrency);

            $user->hourly_rate = $convertedHourlyRate;
            $user->currency = $targetCurrency;

        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $user->update($request->only(['name', 'hourly_rate', 'currency']));
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(null, 204);
    }
}
