<?php

namespace App\Controllers;

use App\Repositories\ReviewRepository;

class ReviewStreamController
{
    /**
     * Send one batch of latest reviews via SSE and exit.
     *
     * Uses a single-shot design instead of an infinite loop to avoid
     * pinning a PHP-FPM worker indefinitely. The frontend EventSource
     * reconnects after a 5-second delay on connection close, so the
     * stream stays "live enough" without exhausting pm.max_children.
     */
    public function stream(): void
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        $reviewRepo = new ReviewRepository();
        $lastId = $reviewRepo->maxId();

        // Fetch the 5 most recent reviews in this batch
        $sinceId = max(0, $lastId - 5);
        $reviews = $reviewRepo->latestForSSE($sinceId, 5);

        $data = array_map(function ($review) {
            return [
                'id'         => (int) $review['id'],
                'book_id'    => (int) $review['book_id'],
                'reviewer'   => $review['reviewer_name'] ?? $review['user_name'] ?? 'Unknown',
                'book_title' => $review['book_title'] ?? '',
                'rating'     => (int) $review['rating'],
                'content'    => mb_substr($review['content'] ?? '', 0, 150),
                'timestamp'  => timeAgo($review['created_at']),
            ];
        }, $reviews);

        // Send one batch
        echo "id: {$lastId}\n";
        echo "event: reviews\n";
        echo 'data: ' . json_encode($data) . "\n\n";

        // Tell EventSource to reconnect after 5 seconds
        echo "retry: 5000\n\n";

        if (ob_get_level()) {
            ob_flush();
        }
        flush();
    }
}
