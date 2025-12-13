<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\FirebaseNotificationService;
use App\Models\DeviceToken;

class SendTestNotifications extends Command
{
    protected $signature = 'notifications:send-test {--title=Test} {--body=Hello} {--limit=10}';
    protected $description = 'Send test push notifications to N devices';

    public function handle(FirebaseNotificationService $firebase)
    {
        $limit = (int)$this->option('limit');
        $tokens = DeviceToken::limit($limit)->pluck('token')->toArray();

        if (empty($tokens)) {
            $this->error('No tokens found.');
            return 1;
        }

        $reports = $firebase->sendToTokens($tokens, $this->option('title'), $this->option('body'));
        $this->info('Reports: ' . json_encode($reports));
        return 0;
    }
}
