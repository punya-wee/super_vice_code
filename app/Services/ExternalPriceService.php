<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ExternalPriceService
{
    private $productApiUrl = 'https://dataapi.moc.go.th/gis-products';
    private $priceApiUrl = 'https://dataapi.moc.go.th/gis-product-price';

    /**
     * Fetch and store price data for vegetables and fruits
     */
    public function fetchAndStoreData()
    {
        try {
            // 1. Fetch products (vegetables and fruits only)
            $products = $this->fetchProducts();
            
            if (empty($products)) {
                \Log::info('No products found');
                return false;
            }

            // 2. Fetch prices for each product (last 6 months)
            $priceData = $this->fetchPrices($products);

            // 3. Store in database
            if (!empty($priceData)) {
                $this->storeExternalPrices($priceData);
                $this->updateLastFetchDate();
                \Log::info('External prices updated successfully', ['count' => count($priceData)]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Failed to fetch external prices', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Fetch products from MOC API (vegetables and fruits only)
     */
    private function fetchProducts()
    {
        try {
            $response = Http::timeout(30)->get($this->productApiUrl);
            
            if (!$response->successful()) {
                throw new \Exception('MOC API error: ' . $response->status());
            }

            $data = $response->json();
            $products = $data['data'] ?? [];

            // Filter for vegetables and fruits
            $filtered = collect($products)->filter(function($product) {
                $category = strtolower($product['category_name'] ?? '');
                return strpos($category, 'ผัก') !== false || strpos($category, 'ผลไม้') !== false;
            })->values()->all();

            return $filtered;
        } catch (\Exception $e) {
            \Log::error('Failed to fetch products', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Fetch prices for products (last 6 months)
     */
    private function fetchPrices($products)
    {
        try {
            $toDate = Carbon::now()->format('Y-m-d');
            $fromDate = Carbon::now()->subMonths(6)->format('Y-m-d');
            
            $priceData = [];

            foreach ($products as $product) {
                $productId = $product['product_id'] ?? null;
                
                if (!$productId) {
                    continue;
                }

                // Fetch price data for this product
                $response = Http::timeout(30)->get($this->priceApiUrl, [
                    'product_id' => $productId,
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $prices = $data['data'] ?? [];

                    foreach ($prices as $price) {
                        $priceData[] = [
                            'product_id' => $price['product_id'] ?? $productId,
                            'product_name' => $price['product_name'] ?? '',
                            'category_name' => $price['category_name'] ?? $product['category_name'] ?? '',
                            'date' => $price['date'] ?? null,
                            'price_min' => $price['price_min'] ?? null,
                            'price_max' => $price['price_max'] ?? null,
                            'price_avg' => isset($price['price_min'], $price['price_max']) 
                                ? round(($price['price_min'] + $price['price_max']) / 2, 2)
                                : null,
                            'fetched_at' => now(),
                        ];
                    }
                }

                // Small delay to avoid rate limiting
                usleep(100000); // 0.1 second
            }

            return $priceData;
        } catch (\Exception $e) {
            \Log::error('Failed to fetch prices', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Store external prices in database
     */
    private function storeExternalPrices($priceData)
    {
        try {
            // Clear old data (keep only last 6 months)
            $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfDay();
            DB::table('external_prices')
                ->where('date', '<', $sixMonthsAgo)
                ->delete();

            // Insert new prices (ignore duplicates)
            DB::table('external_prices')->upsert(
                $priceData,
                ['product_id', 'date'], // unique keys
                ['price_min', 'price_max', 'price_avg', 'fetched_at'] // columns to update
            );

            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to store external prices', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Update last fetch date
     */
    private function updateLastFetchDate()
    {
        try {
            DB::table('system_settings')->updateOrInsert(
                ['key' => 'external_prices_last_fetched'],
                ['value' => now()->toDateTimeString()]
            );
        } catch (\Exception $e) {
            \Log::error('Failed to update last fetch date', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Check if should fetch data (once per day)
     */
    public function shouldFetch()
    {
        try {
            $lastFetch = DB::table('system_settings')
                ->where('key', 'external_prices_last_fetched')
                ->value('value');

            if (!$lastFetch) {
                return true; // First time
            }

            $lastFetchTime = Carbon::parse($lastFetch);
            $now = Carbon::now();

            // Fetch once per day
            return $now->diffInHours($lastFetchTime) >= 24;
        } catch (\Exception $e) {
            return true;
        }
    }

    /**
     * Get latest price data
     */
    public function getLatestPrices($limit = 100)
    {
        try {
            return DB::table('external_prices')
                ->orderBy('date', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to get prices', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get price trend for a product
     */
    public function getPriceTrend($productId, $days = 180)
    {
        try {
            $fromDate = Carbon::now()->subDays($days)->startOfDay();
            
            return DB::table('external_prices')
                ->where('product_id', $productId)
                ->where('date', '>=', $fromDate)
                ->orderBy('date', 'asc')
                ->get();
        } catch (\Exception $e) {
            \Log::error('Failed to get price trend', ['error' => $e->getMessage()]);
            return [];
        }
    }
}
