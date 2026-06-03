<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TabbyService
{
    protected string $secretKey;
    protected string $merchantCode;
    protected string $apiUrl;

    public function __construct()
    {
        $this->secretKey = config('services.tabby.secret_key');
        $this->merchantCode = config('services.tabby.merchant_code');
        $this->apiUrl = config('services.tabby.api_url');
    }

    /**
     * Create Tabby checkout session
     */
    public function createCheckout(array $payload)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
            'Content-Type'  => 'application/json',
        ])->post(
            $this->apiUrl . '/checkout',
            $payload
        );

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    /**
     * Get payment details
     */
    public function getPayment(string $paymentId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->secretKey,
        ])->get(
            $this->apiUrl . '/payments/' . $paymentId
        );

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }
}