<?php

declare(strict_types=1);

namespace App;

class MailhogClient
{
    private string $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function listMessages(int $limit = 20): array
    {
        $data = $this->request();
        if (!isset($data['items']) || !is_array($data['items'])) {
            return [];
        }

        $messages = [];
        foreach ($data['items'] as $item) {
            $from = $item['Content']['Headers']['From'][0] ?? '';
            $to = $item['Content']['Headers']['To'][0] ?? '';
            $subject = $item['Content']['Headers']['Subject'][0] ?? '';
            $body = $item['Content']['Body'] ?? '';
            $snippet = $this->extractPlainText($body);

            if (strlen($snippet) > 120) {
                $snippet = substr($snippet, 0, 120) . '...';
            }

            $messages[] = [
                'id' => (string) ($item['ID'] ?? ''),
                'from' => (string) $from,
                'to' => (string) $to,
                'subject' => (string) $subject,
                'snippet' => (string) $snippet
            ];

            if (count($messages) >= $limit) {
                break;
            }
        }

        return $messages;
    }

    private function extractPlainText(string $body): string
    {
        $lines = explode("\n", $body);
        $textLines = [];
        $inHeaders = true;

        foreach ($lines as $line) {
            if (strpos($line, '--') === 0) {
                $inHeaders = true;
                continue;
            }

            if ($inHeaders && strpos($line, ':') !== false) {
                continue;
            }

            if ($inHeaders && trim($line) === '') {
                $inHeaders = false;
                continue;
            }

            if (!$inHeaders && trim($line) !== '') {
                $text = trim($line);
                if (!empty($text) && strpos($text, 'Content-') !== 0) {
                    $textLines[] = $text;
                }
            }
        }

        return trim(implode(' ', $textLines));
    }

    /**
     * @return array<string, mixed>
     */
    private function request(): array
    {
        $context = stream_context_create([
            'http' => [
                'timeout' => 3
            ]
        ]);

        $raw = @file_get_contents($this->apiUrl, false, $context);
        if ($raw === false) {
            return [];
        }

        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
