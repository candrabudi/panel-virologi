<?php

namespace Database\Seeders;

use App\Models\AiResourceRoute;
use App\Models\CyberSecurityService;
use App\Models\Ebook;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AiResourceRouteSeeder extends Seeder
{
    public function run(): void
    {
        $scope = 'cybersecurity';

        $this->seedProducts($scope);
        $this->seedServices($scope);
        $this->seedEbooks($scope);
    }

    private function seedProducts(string $scope): void
    {
        if (!class_exists(Product::class)) {
            return;
        }

        Product::where('is_active', true)
            ->select('id', 'name')
            ->chunk(50, function ($items) use ($scope) {
                foreach ($items as $item) {
                    $keywords = $this->extractKeywords($item->name);

                    foreach ($keywords as $kw) {
                        AiResourceRoute::updateOrCreate(
                            [
                                'scope_code' => $scope,
                                'resource_type' => 'product',
                                'resource_id' => $item->id,
                                'keyword' => $kw,
                            ],
                            [
                                'weight' => 10,
                                'is_active' => true,
                            ]
                        );
                    }
                }
            });
    }

    private function seedServices(string $scope): void
    {
        if (!class_exists(CyberSecurityService::class)) {
            return;
        }

        CyberSecurityService::where('is_active', true)
            ->select('id', 'name')
            ->chunk(50, function ($items) use ($scope) {
                foreach ($items as $item) {
                    $keywords = $this->extractKeywords($item->name);

                    foreach ($keywords as $kw) {
                        AiResourceRoute::updateOrCreate(
                            [
                                'scope_code' => $scope,
                                'resource_type' => 'service',
                                'resource_id' => $item->id,
                                'keyword' => $kw,
                            ],
                            [
                                'weight' => 15,
                                'is_active' => true,
                            ]
                        );
                    }
                }
            });
    }

    private function seedEbooks(string $scope): void
    {
        if (!class_exists(Ebook::class)) {
            return;
        }

        Ebook::where('is_active', true)
            ->select('id', 'title')
            ->chunk(50, function ($items) use ($scope) {
                foreach ($items as $item) {
                    $keywords = $this->extractKeywords($item->title);

                    foreach ($keywords as $kw) {
                        AiResourceRoute::updateOrCreate(
                            [
                                'scope_code' => $scope,
                                'resource_type' => 'ebook',
                                'resource_id' => $item->id,
                                'keyword' => $kw,
                            ],
                            [
                                'weight' => 12,
                                'is_active' => true,
                            ]
                        );
                    }
                }
            });
    }

    private function extractKeywords(string $text): array
    {
        $text = Str::lower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text);
        $text = trim(preg_replace('/\s+/u', ' ', $text));

        if ($text === '') {
            return [];
        }

        $words = explode(' ', $text);

        $stopwords = [
            'dan', 'atau', 'the', 'of', 'for', 'to', 'with', 'in',
            'service', 'services', 'solution', 'solutions',
            'product', 'products', 'ebook', 'buku', 'modul',
            'cyber', 'security', 'keamanan', 'sistem',
        ];

        $keywords = [];

        foreach ($words as $w) {
            if (mb_strlen($w) < 3) {
                continue;
            }

            if (in_array($w, $stopwords, true)) {
                continue;
            }

            $keywords[] = $w;
        }

        $keywords[] = $text;

        return array_values(array_unique($keywords));
    }
}
