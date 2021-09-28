<?php

namespace App\Services;

use App\Models\TelegramUser;
use App\Repositories\TelegramLogsRepository;
use App\Repositories\TelegramUserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;
use Psr\Log\LoggerInterface;

class WarehouseService
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var TelegramNotificationService
     */
    private TelegramNotificationService $telegramNotificationService;

    /**
     * @var TelegramUserRepository
     */
    private TelegramUserRepository $telegramUserRepository;

    /**
     * @var TelegramLogsRepository
     */
    private TelegramLogsRepository $telegramLogsRepository;

    /**
     * @var TelegramUser
     */
    private TelegramUser $user;

    /**
     * @var string
     */
    private string $command;

    /**
     * WarehouseService constructor.
     * @param Log $logger
     * @param TelegramNotificationService $telegramNotificationService
     * @param TelegramUserRepository $telegramUserRepository
     * @param TelegramLogsRepository $telegramLogsRepository
     */
    public function __construct(
        Log $logger,
        TelegramNotificationService $telegramNotificationService,
        TelegramUserRepository $telegramUserRepository,
        TelegramLogsRepository $telegramLogsRepository
    ) {
        $this->logger = $logger::channel('bot');
        $this->telegramNotificationService = $telegramNotificationService;
        $this->telegramUserRepository = $telegramUserRepository;
        $this->telegramLogsRepository = $telegramLogsRepository;
    }

    /**
     * @param array $params
     */
    public function getRouteCommand(array $params): void
    {
        $query = $this
            ->telegramUserRepository
            ->query();

        $query = $this
            ->telegramUserRepository
            ->whereTelegramUserId($query, $params["user_id"]);

        $user = $query->first();

        if ($user) {

            $this->user = $user;
            $this->command = $params["text_in"];

            $user->update([
                'first_name' => $params["first_name"],
                'username' => $params["username"],
            ]);

            if ( preg_match('/^\/(help)/', $params["text_in"], $single_command) ) {
                $this->executeCommandHelp( $params["user_id"] );
            }

            if ( preg_match('/^[0-9]{1,2}[%]{1}[0-9]{1,5}$/', $params["text_in"], $code) ) {
                $this->executeCommandFindCell( $code );
            }
        }
    }

    /**
     * Send help instruction on user by id
     * @param int $userId
     */
    public function executeCommandHelp(int $userId): void
    {
        $msg = "Инструкция\n\n";
        $msg .= "/help - вывод команд";

        try {
            $this
                ->telegramLogsRepository
                ->store(
                    [
                        "telegram_user_id" => $this->user->telegram_user_id,
                        "command" => $this->command,
                    ]
                );

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $userId);
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * @param $param
     * @param $userId
     */
    public function executeCommandFindCell($code): void
    {
        $res = DB::connection("dax")->select("

        SELECT DISTINCT INVENTDIM.INVENTBATCHID, INVENTTABLE.NAMEALIAS, INVENTDIM.RUK_INVENTCOLORID, INVENTDIM.WMSLOCATIONID FROM INVENTSUM WITH (READUNCOMMITTED)
        LEFT LOOP JOIN INVENTDIM ON INVENTDIM.INVENTDIMID = INVENTSUM.INVENTDIMID
        JOIN INVENTTABLE ON INVENTSUM.ITEMID = INVENTTABLE.ITEMID
            WHERE	INVENTSUM.PARTITION = 5637144576 AND INVENTSUM.DATAAREAID = 'rlc'
		AND INVENTDIM.PARTITION = 5637144576 AND INVENTDIM.DATAAREAID = 'rlc'
		AND INVENTSUM.PHYSICALINVENT != 0
		AND INVENTSUM.CLOSEDQTY = 0
		AND INVENTSUM.CLOSED = 0
		AND INVENTDIM.INVENTBATCHID LIKE :number
        ", [ "number" => $code[0] ]);

        try {
            $msg = "";

            foreach ($res as $item) {
                $msg .= $item->INVENTBATCHID . PHP_EOL;
                $msg .= $item->NAMEALIAS . PHP_EOL;
                $msg .= $item->RUK_INVENTCOLORID . PHP_EOL;
                $msg .= $item->WMSLOCATIONID . PHP_EOL;
                $msg .= PHP_EOL;
            }

            $this
                ->telegramLogsRepository
                ->store(
                    [
                        "telegram_user_id" => $this->user->telegram_user_id,
                        "command" => $this->command,
                    ]
                );

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $this->user->telegram_user_id);
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }
}
