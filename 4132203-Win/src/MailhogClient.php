<?php

declare(strict_types=1);

namespace App;

class MailhogClient
{
    // URL ของ MailHog API เช่น http://mailhog:8025/api/v2/messages
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
        // ดึงข้อมูลดิบจาก API ก่อนแปลงเป็นรูปแบบที่หน้าเว็บใช้งานง่าย
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

            // ตัดข้อความให้สั้นพอดูเป็น preview
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
        // แยกบรรทัดจากเนื้อหา MIME เพื่อคัดเฉพาะข้อความอ่านง่าย
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
        // ตั้ง timeout สั้นเพื่อไม่ให้หน้าเว็บค้างเมื่อ API มีปัญหา
        $context = stream_context_create([
            'http' => [
                'timeout' => 3
            ]
        ]);

        // ใช้ @ เพื่อไม่ให้ warning ดิบหลุดไปหน้าเว็บ
        $raw = @file_get_contents($this->apiUrl, false, $context);
        if ($raw === false) {
            return [];
        }

        // แปลง JSON เป็น array และคืนค่าว่างเมื่อรูปแบบไม่ถูกต้อง
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
