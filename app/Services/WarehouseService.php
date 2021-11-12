<?php

namespace App\Services;

use App\Models\TelegramUser;
use App\Repositories\TelegramLogsRepository;
use App\Repositories\TelegramUserRepository;
use App\Sql\SqlScripts;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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
    public string $codeNumber;

    /**
     * WarehouseService constructor.
     * @param TelegramNotificationService $telegramNotificationService
     * @param TelegramUserRepository $telegramUserRepository
     * @param TelegramLogsRepository $telegramLogsRepository
     */
    public function __construct(
        TelegramNotificationService $telegramNotificationService,
        TelegramUserRepository $telegramUserRepository,
        TelegramLogsRepository $telegramLogsRepository
    ) {
        $this->telegramNotificationService = $telegramNotificationService;
        $this->telegramUserRepository = $telegramUserRepository;
        $this->telegramLogsRepository = $telegramLogsRepository;
        $this->codeNumber = '';
    }

    /**
     * @param array $params
     */
    public function getRouteCommand(array $params): void
    {
        $this->logger = Log::channel('bot');

        if (!$params["user_id"]) {
            return;
        }

        $query = $this
            ->telegramUserRepository
            ->query();

        $query = $this
            ->telegramUserRepository
            ->whereTelegramUserId($query, $params["user_id"]);

        $user = $query->first();

        if ( preg_match('/^\/(start)/', $params["text_in"], $single_command) ) {
            $this->telegramNotificationService->sendMessageToTelegram("Вас приветствует бот РК", $params["user_id"]);
            return;
        }

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
                $this->executeCommandFindCell2( $code[0] );
                return;
            }

            if ( preg_match('/^([0-9]{1,2}[%]{1}[0-9]{4,5})[*]$/', $params["text_in"], $code) ) {
                $this->executeCommandFindCellWithColor($code[1]);
                return;
            }

            if ( preg_match('/^[0-9]{4,5}$/', $params["text_in"], $code) ) {
                $this->executeCommandFindCellByOnlyNumber( $code[0] );
                return;
            }

            if ( preg_match('/^([0-9]{4,5})[*]$/', $params["text_in"], $code) ) {
                $this->executeCommandFIndCellByOnlyNumberWithColor($code[1]);
                return;
            }

            if ( preg_match('/^([0-9]{1,2}[%]{1}[0-9]{4,10})[@]$/', $params["text_in"], $code) ) {
                $this->executeCommandFindCellWithColorAndUser($code[1]);
                return;
            }

            if ( preg_match('/^([0-9]{4,10})[@]$/', $params["text_in"], $code) ) {
                $this->executeCommandFindCellByOnlyNumberWithUser($code[1]);
                return;
            }

//            if ( preg_match('/^([0-9a-zA-Z_]{1,20})[#]$/', $params["text_in"], $code) ) {
//                $this->executeCommandFindCellWithPartString($code[1]);
//                return;
//            }

            if ( preg_match('/^\/(adduser)@(.*)/', $params["text_in"], $newUserId) ) {

                TimerExecuteService::Start();

                if($this->user->is_admin == 1) {
                    $this->executeCommandAddUser( $newUserId[2] );
                } else {
                    $this
                        ->telegramNotificationService
                        ->sendMessageToTelegram("У Вас недостаточно прав!", $this->user->telegram_user_id);
                    $this->logger->info("Пользователь " . $this->user->telegram_user_id . " пытался добавить пользователя");
                }

                $this->setLogUserCommand(TimerExecuteService::Stop());
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
        TimerExecuteService::Start();

        $msg = "Инструкция\n\n";
        $msg .= "/help - вывод команд.\n\n";
        $msg .= "12345 - показать партию с номером 12345 за текущий год.\n\n";
        $msg .= "20%12345 - показать партию с номером 12345 за 2020 год.\n";
        $msg .= "первые два числа указывают на год.\n\n";
        $msg .= "20%12345* - найти партию с цветом за 2020 год.\n\n";
        $msg .= "12345* - найти партию с цветом за текущий год.\n\n";

        try {
            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $userId);

            $this->setLogUserCommand(TimerExecuteService::Stop());
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
            $this->executeCommandFindCell2($this->getCurrentYear() . '%' . $code);
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Find part with color by only number
     * @param $code
     */
    public function executeCommandFIndCellByOnlyNumberWithColor($code): void
    {
        $this->codeNumber = $code;
        try {
            $this->executeCommandFindCellWithColor($this->getCurrentYear() . '%' . $code);
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
        TimerExecuteService::Start();

        $message = "Вы добавлены в телеграм бот";

        try {
            $existUser =  $this->getTelegramUser($newUser);

            if ($existUser) {
                $this
                    ->telegramNotificationService
                    ->sendMessageToTelegram("Такой пользователь уже существует", $this->user->telegram_user_id);
                return;
            }

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

            $this->setLogUserCommand(TimerExecuteService::Stop());

        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Find part by code without color
     * @param $code
     */
    public function executeCommandFindCell2($code): void
    {
        TimerExecuteService::Start();

        $sql = SqlScripts::getSqlQuery();

        $data = DB::connection("dax")->select($sql, [ "number" => $code]);

        $isExistPart = $this->partNotFound($data);

        if (!$isExistPart) {
            return;
        }

        try {
            $msg = "";

            foreach ($data as $item) {
                $msg .= $item->BATCH . PHP_EOL;
                $msg .= $item->NAMEALIAS . PHP_EOL;
                $msg .= "Яч: " . $item->WMSLOCATION . PHP_EOL;
                $msg .= "НЗ: " . $item->LICENSE . PHP_EOL;
                $msg .= PHP_EOL;
            }

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $this->user->telegram_user_id);

            $this->setLogUserCommand(TimerExecuteService::Stop());
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Get part by number with color
     * @param string $number
     */
    public function executeCommandFindCellWithColor(string $number): void
    {
        TimerExecuteService::Start();

        $sql = SqlScripts::getSqlQueryWithColor();

        $data = DB::connection("dax")->select($sql, ["number" => $number]);

        $isExistPart = $this->partNotFound($data);

        if (!$isExistPart) {
            return;
        }

        try {
            $msg = "";

            foreach ($data as $item) {
                $msg .= $item->BATCH . PHP_EOL;
                $msg .= $item->NAMEALIAS . PHP_EOL;
                $msg .= "Конфиг: " . $item->CONFIGID . PHP_EOL;
                $msg .= "Цвет: " . $item->COLORID . PHP_EOL;
                $msg .= "Яч: " . $item->WMSLOCATION . PHP_EOL;
                $msg .= "НЗ: " . $item->LICENSE . PHP_EOL;
                $msg .= PHP_EOL;
            }

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $this->user->telegram_user_id);

            $this->setLogUserCommand(TimerExecuteService::Stop());
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Find part with color and user name by number and year
     * @param string $number
     */
    public function executeCommandFindCellWithColorAndUser(string $number): void
    {
        TimerExecuteService::Start();

        $sql = SqlScripts::getSqlQueryByPartialWithUsers();

        $data = DB::connection("dax")->select($sql, ["number" => $number]);

        $isExistPart = $this->partNotFound($data);

        if (!$isExistPart) {
            return;
        }

        try {
            $msg = "";

            foreach ($data as $item) {
                $msg .= $item->BATCH . PHP_EOL;
                $msg .= $item->NAMEALIAS . PHP_EOL;
                $msg .= "Цвет: " . $item->COLORID . PHP_EOL;
                $msg .= "Яч: " . $item->WMSLOCATION . PHP_EOL;
                $msg .= "НЗ: " . $item->LICENSE . PHP_EOL;
                $msg .= "ФИО: " . $item->USERNAME . PHP_EOL;
                $msg .= PHP_EOL;
            }

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $this->user->telegram_user_id);

            $this->setLogUserCommand(TimerExecuteService::Stop());
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Find part with color and user name by only number
     * @param $code
     */
    public function executeCommandFindCellByOnlyNumberWithUser($code): void
    {
        $this->codeNumber = $code;
        try {
            $this->executeCommandFindCellWithColorAndUser($this->getCurrentYear() . '%' . $code);
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Get part by number with number and word
     * @param string $number
     */
    public function executeCommandFindCellWithPartString(string $number): void
    {
        TimerExecuteService::Start();

        $sql = SqlScripts::getSqlQueryByPartial();

        $data = DB::connection("dax")->select($sql, ["number" => '%' . $number . '%']);

        $isExistPart = $this->partNotFound($data);

        if (!$isExistPart) {
            return;
        }

        try {
            $msg = "";

            foreach ($data as $item) {
                $msg .= $item->BATCH . PHP_EOL;
                $msg .= $item->NAMEALIAS . PHP_EOL;
                $msg .= "Яч: " . $item->WMSLOCATION . PHP_EOL;
                $msg .= "НЗ: " . $item->LICENSE . PHP_EOL;
                $msg .= PHP_EOL;
            }

            $this
                ->telegramNotificationService
                ->sendMessageToTelegram($msg, $this->user->telegram_user_id);

            $this->setLogUserCommand(TimerExecuteService::Stop());
        } catch (Exception $ex) {
            $this->logger->info($ex->getMessage());
        }
    }

    /**
     * Set log user command
     * @param null $time
     * @return Model
     */
    public function setLogUserCommand($time = null): Model
    {
        return $this
            ->telegramLogsRepository
            ->store(
                [
                    "telegram_user_id" => $this->user->telegram_user_id,
                    "command" => $this->command,
                    "execute_time" => $time ?? 0,
                ]
            );
    }

    /**
     * Find part and return true or false
     * @param array $data
     * @return bool
     */
    public function partNotFound(array $data): bool
    {
        if (!$data) {
            $this
                ->telegramNotificationService
                ->sendMessageToTelegram("Партия с номером " . $this->codeNumber  . " не найдена", $this->user->telegram_user_id);
            return false;
        }

        return true;
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

    /**
     * Get user by id or return null
     * @param $userId
     * @return Model|null
     */
    public function getTelegramUser($userId): ?Model
    {
        $query = $this
            ->telegramUserRepository
            ->query();

        $query = $this
            ->telegramUserRepository
            ->whereTelegramUserId($query, $userId);

        return $query->first();
    }
}
