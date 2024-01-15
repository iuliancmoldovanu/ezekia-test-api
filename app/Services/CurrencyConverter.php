<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    public function convert($amount, $fromCurrency, $toCurrency): float|int
    {
        if($fromCurrency === $toCurrency){ // nothing to convert
            return $amount;
        }
        $driver = config('app.currency_converter_driver', 'local');

        if ($driver === 'local') {
            $amount = $this->convertLocally($amount, $fromCurrency, $toCurrency);
        } elseif ($driver === 'api') {
            $amount = $this->convertUsingApi($amount, $fromCurrency, $toCurrency);
        } else {
            throw new \InvalidArgumentException('Invalid currency converter driver');
        }
        return round($amount, 2);
    }

    private function convertLocally($amount, $fromCurrency, $toCurrency): float|int
    {
        // Implement local currency conversion logic based on exchange rates
        $exchangeRates = [
            'GBP' => ['USD' => 1.3, 'EUR' => 1.1],
            'EUR' => ['GBP' => 0.9, 'USD' => 1.2],
            'USD' => ['GBP' => 0.7, 'EUR' => 0.8],
        ];

        if (!isset($exchangeRates[$fromCurrency][$toCurrency])) {
            throw new \InvalidArgumentException('Invalid currency pair for conversion');
        }

        return $amount * $exchangeRates[$fromCurrency][$toCurrency];
    }

    private function convertUsingApi($amount, $fromCurrency, $toCurrency): float|int
    {
        // Logic to make API call to a currency exchange service
        $response = Http::get('http://data.fixer.io/api/latest', [
            'cbase' => $fromCurrency,
            'access_key' => config("app.exchange_key"),
        ]);

        $data = $response->json();

        if (!isset($data['rates'][$toCurrency])) {
            throw new \RuntimeException('Failed to retrieve exchange rate from API');
        }

        return $amount * $data['rates'][$toCurrency];
    }
}
