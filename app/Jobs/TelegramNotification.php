<?php

namespace App\Jobs;

use App\Repositories\TelegramLogsRepository;
use App\Repositories\TelegramUserRepository;
use App\Services\TelegramNotificationService;
use App\Services\WarehouseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class TelegramNotification implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var int
     */
    public $tries = 5; // Количество попыток выполнения задания

    /**
     * Input params
     * @var array
     */
    public array $data;

    /**
     * @var LoggerInterface
     */
    public LoggerInterface $loggerJob;

    /**
     * @var LoggerInterface
     */
    public LoggerInterface $loggerBot;

    /**
     * @var TelegramNotificationService
     */
    public TelegramNotificationService $telegramNotificationService;

    /**
     * @var TelegramUserRepository
     */
    public TelegramUserRepository $telegramUserRepository;

    /**
     * @var TelegramLogsRepository
     */
    public TelegramLogsRepository $telegramLogsRepository;

    /**
     * @var WarehouseService
     */
    public WarehouseService $warehouse;

    /**
     * @var string
     */
    public string $codeNumber = '';

    /**
     * TelegramNotification constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->data = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->loggerJob =  Log::channel('job');

        $this->telegramNotificationService = new TelegramNotificationService();
        $this->telegramUserRepository = new TelegramUserRepository();
        $this->telegramLogsRepository = new TelegramLogsRepository();

        $this->warehouse = new WarehouseService(
            $this->telegramNotificationService,
            $this->telegramUserRepository,
            $this->telegramLogsRepository
        );

        $this->loggerJob->info($this->data);
        $this->warehouse->getRouteCommand($this->data);
    }
}
