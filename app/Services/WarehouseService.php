<?php

namespace App\Services;

use App\Models\TelegramUser;
use App\Repositories\TelegramLogsRepository;
use App\Repositories\TelegramUserRepository;
use Carbon\Carbon;
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
     * @var string
     */
    private string $codeNumber;

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
                'first_name' => $params["first_name"] ?? null,
                'username' => $params["username"] ?? null,
            ]);

            if ( preg_match('/^\/(help)/', $params["text_in"], $single_command) ) {
                $this->executeCommandHelp( $params["user_id"] );
                return;
            }

            if ( preg_match('/^[0-9]{1,2}[%]{1}[0-9]{4,5}$/', $params["text_in"], $code) ) {
                $this->executeCommandFindCell( $code[0] );
                return;
            }

            if ( preg_match('/^[0-9]{4,5}$/', $params["text_in"], $code) ) {
                $this->executeCommandFindCellByOnlyNumber( $code[0] );
                return;
            }

            if ( preg_match('/^\/(adduser)@(.*)/', $params["text_in"], $newUserId) ) {
                if($this->user->is_admin == 1) {
                    $this->executeCommandAddUser( $newUserId[2] );
                } else {
                    $this
                        ->telegramNotificationService
                        ->sendMessageToTelegram("У Вас недостаточно прав!", $this->user->telegram_user_id);
                    $this->logger->info("Пользователь " . $this->user->telegram_user_id . " пытался добавить пользователя");
                }
                return;
            }

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram("Вы ввели некорректные данные", $this->user->telegram_user_id);
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
            $this->setLogUserCommand();

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $userId);
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Get cell by mask query
     * @param $code
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
        ", [ "number" => $code ]);

        if (!$res) {
            $this
                ->telegramNotificationService
                ->sendMessageToTelegram("Партия с номером " . $this->codeNumber  . " не найдена", $this->user->telegram_user_id);
            return;
        }

        try {
            $msg = "";

            foreach ($res as $item) {
                $msg .= $item->INVENTBATCHID . PHP_EOL;
                $msg .= $item->NAMEALIAS . PHP_EOL;
                $msg .= $item->RUK_INVENTCOLORID . PHP_EOL;
                $msg .= $item->WMSLOCATIONID . PHP_EOL;
                $msg .= PHP_EOL;
            }

            $this->setLogUserCommand();

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $this->user->telegram_user_id);
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Get cell by only number
     * @param int $code
     */
    public function executeCommandFindCellByOnlyNumber($code): void
    {
        $this->codeNumber = $code;
        try {
            $this->executeCommandFindCell($this->getCurrentYear() . '%' . $code);
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Add new user in telegram bot
     * @param $newUser
     */
    public function executeCommandAddUser($newUser)
    {
        $message = "Вы добавлены в телеграм бот";

        try {
            $user = $this
                ->telegramUserRepository
                ->store(["telegram_user_id" => $newUser]);
            if ($user) {
                $this
                    ->telegramNotificationService
                    ->sendMessageToTelegram($message, $newUser);
                $this
                    ->telegramNotificationService
                    ->sendMessageToTelegram("Пользователь добавлен", $this->user->telegram_user_id);
            }
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Set log user command
     */
    public function setLogUserCommand(): void
    {
        $this
            ->telegramLogsRepository
            ->store(
                [
                    "telegram_user_id" => $this->user->telegram_user_id,
                    "command" => $this->command,
                ]
            );
    }

    /**
     * Get current year in format last two number
     * Example 2021 =>  return 21
     * @return string
     */
    public function getCurrentYear(): string
    {
        $date = Carbon::now()->format("Y");
        return $date[2] . $date[3];
    }
}
