<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Post;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class CrawlRssCommand extends Command
{
    protected $signature = 'app:crawl-tuoitre';
    protected $description = 'Crawl tin công nghệ từ Tuoi Tre (Kết hợp RSS và Scrape)';

    public function handle()
    {
        $limit = 30; // <-- Chỉ crawl 5 bài đầu tiên

        $feedUrl = 'https://tuoitre.vn/rss/cong-nghe.rss';
        $this->info("Fetching RSS from: $feedUrl (Limit: $limit posts)");

        try {
            $response = Http::get($feedUrl);
            if (!$response->successful()) {
                $this->error("Failed to fetch RSS feed. Status: " . $response->status());
                return 1;
            }

            $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($xml === false) {
                $this->error("Failed to parse XML.");
                return 1;
            }

            $items = $xml->xpath('//channel/item');
            if ($items === false || empty($items)) {
                $this->error("Could not find any items in the feed.");
                return 1;
            }

            $this->info("Found " . count($items) . " items. Starting scrape...");

            $scraperClient = new HttpBrowser(HttpClient::create());

            $counter = 0;

            foreach ($items as $item) {
                
                if ($counter >= $limit) {
                    $this->info("Đã đạt giới hạn $limit bài. Dừng lại.");
                    break; 
                }

                $title = (string) $item->title;
                $link = (string) $item->link;
                $descriptionHtml = (string) $item->description;

                // 1. Lấy ảnh
                $coverImage = null;
                try {
                    $descCrawler = new Crawler($descriptionHtml);
                    $imageNode = $descCrawler->filter('img')->first();
                    if ($imageNode->count() > 0) {
                        $coverImage = $imageNode->attr('src');
                    }
                } catch (\Exception $e) { /* Bỏ qua */ }

                // 2. Lấy nội dung đầy đủ
                $contentHtml = '';
                try {
                    $postPageCrawler = $scraperClient->request('GET', $link);
                    $contentNode = $postPageCrawler->filter('div.detail-content'); 
                    if ($contentNode->count() > 0) {
                        $contentHtml = $contentNode->html();
                    } else {
                        $this->warn("Không tìm thấy 'div.detail-content' cho: $link");
                    }
                    sleep(1); 
                } catch (\Exception $e) {
                    $this->warn("Lỗi khi scrape chi tiết: $link | " . $e->getMessage());
                }

                // 3. Lưu vào DB
                Post::updateOrCreate(
                    ['source_url' => $link],
                    [
                        'title' => $title,
                        'description' => strip_tags($descriptionHtml),
                        'content' => $contentHtml,
                        'cover_image' => $coverImage,
                    ]
                );

                $this->info("Đã lưu: $title (Ảnh: " . ($coverImage ? 'Có' : 'Không') . ")");

                $counter++;
            }

        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }

        $this->info('Successfully crawled Tuoi Tre!');
        return 0;
    }
}