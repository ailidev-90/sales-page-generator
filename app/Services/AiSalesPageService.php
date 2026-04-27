<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class AiSalesPageService
{
    /**
     * @return array{content: array<string, mixed>, used_fallback: bool}
     */
    public function generate(array $input): array
    {
        if (! config('services.openai.key')) {
            return [
                'content' => $this->fallbackContent($input),
                'used_fallback' => true,
            ];
        }

        try {
            $content = $this->generateWithOpenAi($input);

            return [
                'content' => $content,
                'used_fallback' => false,
            ];
        } catch (Throwable) {
            return [
                'content' => $this->fallbackContent($input),
                'used_fallback' => true,
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function generateWithOpenAi(array $input): array
    {
        $response = Http::withToken(config('services.openai.key'))
            ->acceptJson()
            ->timeout(30)
            ->retry(1, 300)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'temperature' => 0.75,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a senior conversion copywriter. Return only valid JSON for a complete sales landing page. No markdown, no commentary.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->prompt($input),
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('OpenAI request failed.');
        }

        $raw = (string) data_get($response->json(), 'choices.0.message.content', '');
        $decoded = $this->decodeJson($raw);

        return $this->normalizeContent($decoded, $input);
    }

    private function prompt(array $input): string
    {
        return 'Generate a JSON object with exactly these keys: headline, subheadline, product_description, benefits, features, social_proof_placeholder, pricing_display, cta_text. '
            .'benefits and features must be arrays of concise strings. Use the selected tone and template as copy direction, but do not include HTML. '
            .'Product data: '.json_encode([
                'product_name' => Arr::get($input, 'product_name'),
                'description' => Arr::get($input, 'description'),
                'key_features' => Arr::get($input, 'key_features'),
                'target_audience' => Arr::get($input, 'target_audience'),
                'price' => Arr::get($input, 'price'),
                'unique_selling_points' => Arr::get($input, 'unique_selling_points'),
                'template' => Arr::get($input, 'template', 'modern'),
                'tone' => Arr::get($input, 'tone', 'professional'),
            ], JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJson(string $raw): array
    {
        $raw = trim($raw);

        if (Str::startsWith($raw, '```')) {
            $raw = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $raw) ?? $raw;
        }

        $decoded = json_decode($raw, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('AI response was not valid JSON.');
        }

        return $decoded;
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeContent(array $content, array $input): array
    {
        $fallback = $this->fallbackContent($input);

        return [
            'headline' => $this->cleanString($content['headline'] ?? $fallback['headline']),
            'subheadline' => $this->cleanString($content['subheadline'] ?? $fallback['subheadline']),
            'product_description' => $this->cleanString($content['product_description'] ?? $fallback['product_description']),
            'benefits' => $this->cleanList($content['benefits'] ?? []) ?: $fallback['benefits'],
            'features' => $this->cleanList($content['features'] ?? []) ?: $fallback['features'],
            'social_proof_placeholder' => $this->cleanString($content['social_proof_placeholder'] ?? $fallback['social_proof_placeholder']),
            'pricing_display' => $this->cleanString($content['pricing_display'] ?? $fallback['pricing_display']),
            'cta_text' => $this->cleanString($content['cta_text'] ?? $fallback['cta_text']),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function fallbackContent(array $input): array
    {
        $product = $this->cleanString($input['product_name'] ?? 'Your Offer');
        $audience = $this->cleanString($input['target_audience'] ?? 'growing teams');
        $description = $this->cleanString($input['description'] ?? '');
        $price = $this->cleanString($input['price'] ?? '');
        $usp = $this->cleanString($input['unique_selling_points'] ?? '');
        $tone = $this->cleanString($input['tone'] ?? 'professional');

        $features = $this->cleanList($this->splitTextarea($input['key_features'] ?? ''));

        if ($features === []) {
            $features = [
                'Fast setup with a workflow designed for daily use',
                'Clear outcomes your audience can understand immediately',
                'Flexible enough to support different customer needs',
            ];
        }

        $benefits = collect($features)
            ->take(4)
            ->map(fn (string $feature): string => 'Turn '.$feature.' into a measurable advantage for '.$audience.'.')
            ->values()
            ->all();

        if ($usp !== '') {
            array_unshift($benefits, $usp);
        }

        return [
            'headline' => $this->headlineForTone($product, $audience, $tone),
            'subheadline' => 'A focused solution for '.$audience.' that makes the value of '.$product.' clear from the first click.',
            'product_description' => $description !== ''
                ? $description
                : $product.' helps '.$audience.' move faster, explain their value clearly, and convert interest into action.',
            'benefits' => array_slice($benefits, 0, 4),
            'features' => array_slice($features, 0, 6),
            'social_proof_placeholder' => 'Trusted by teams who want a clearer path from first impression to committed customer.',
            'pricing_display' => $price !== '' ? $price : 'Contact us for pricing',
            'cta_text' => $this->ctaForTone($tone),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function splitTextarea(?string $value): array
    {
        if (! $value) {
            return [];
        }

        return preg_split('/[\r\n,]+/', $value) ?: [];
    }

    /**
     * @param mixed $value
     * @return array<int, string>
     */
    private function cleanList(mixed $value): array
    {
        if (is_string($value)) {
            $value = $this->splitTextarea($value);
        }

        if (! is_array($value)) {
            return [];
        }

        return collect($value)
            ->map(fn (mixed $item): string => $this->cleanListItem($item))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function cleanListItem(mixed $item): string
    {
        if (is_array($item)) {
            $item = $item['text'] ?? $item['title'] ?? $item['name'] ?? $item['description'] ?? '';
        }

        if (! is_scalar($item) && ! $item instanceof \Stringable) {
            return '';
        }

        return $this->cleanString((string) $item);
    }

    private function cleanString(mixed $value): string
    {
        if (is_array($value)) {
            return '';
        }

        if (! is_scalar($value) && ! $value instanceof \Stringable) {
            return '';
        }

        $value = (string) $value;

        return trim(strip_tags($value));
    }

    private function headlineForTone(string $product, string $audience, string $tone): string
    {
        return match ($tone) {
            'friendly' => 'Meet '.$product.', built to make life easier for '.$audience,
            'persuasive' => 'Get more from every opportunity with '.$product,
            default => $product.' for '.$audience.' who need results',
        };
    }

    private function ctaForTone(string $tone): string
    {
        return match ($tone) {
            'friendly' => 'Start today',
            'persuasive' => 'Claim your advantage',
            default => 'Get started',
        };
    }
}
