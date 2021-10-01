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

//            if ( preg_match('/^\/(test)/', $params["text_in"], $single_command) ) {
//                $this
//                    ->telegramNotificationService
//                    ->sendMessageToTelegram("Привет!", $this->user->telegram_user_id);
//                return;
//            }

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
        $msg .= "/help - вывод команд\n";
        $msg .= "12345 - показать партию с номером 12345 за текущий год\n\n";
        $msg .= "20%12345 - показать партию с номером 12345 за 2020год год\n";
        $msg .= "первые два числа указывают на год\n\n";

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
            $this->executeCommandFindCell2($this->getCurrentYear() . '%' . $code);
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

        // todo Сделать проверку, если пользователь с telegram_user_id есть в БД,
        // todo отправляем уведомление о том, что пользователь уже есть в базе

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

    public function executeCommandFindCell2($code)
    {
        $data = DB::connection("dax")->select("
        SET NOCOUNT ON;
        IF OBJECT_ID('tempdb.dbo.#Initial') IS NOT NULL
	    DROP TABLE #Initial;

        SELECT INVENTDIM.INVENTBATCHID as Batch,
          CAST((COALESCE(INVENTTABLE_PT.NAMEALIAS,INVENTTABLE.NAMEALIAS)) as nvarchar(max)) as NAMEALIAS,
          CAST(INVENTDIM.WMSLOCATIONID as nvarchar(max)) as WMSLOCATION,
          CAST(INVENTDIM.LICENSEPLATEID as nvarchar(max)) as LICENSE,
          ROW_NUMBER() over(partition by INVENTDIM.INVENTBATCHID order by INVENTDIM.INVENTBATCHID) as rn
        INTO #Initial
        FROM INVENTSUM INVENTSUM WITH (READUNCOMMITTED)
        LEFT LOOP JOIN INVENTDIM INVENTDIM ON INVENTDIM.INVENTDIMID = INVENTSUM.INVENTDIMID
        JOIN INVENTTABLE INVENTTABLE ON INVENTSUM.ITEMID = INVENTTABLE.ITEMID
        LEFT JOIN ProdTable ProdTable ON INVENTDIM.INVENTBATCHID = ProdTable.ProdID
        LEFT JOIN INVENTTABLE INVENTTABLE_PT ON ProdTable.ITEMID = INVENTTABLE_PT.ITEMID
               WHERE  INVENTSUM.PARTITION = 5637144576 AND INVENTSUM.DATAAREAID = 'rlc'
                     AND INVENTDIM.PARTITION = 5637144576 AND INVENTDIM.DATAAREAID = 'rlc'
                     AND INVENTSUM.PHYSICALINVENT != 0
                     AND INVENTSUM.CLOSEDQTY = 0
                     AND INVENTSUM.CLOSED = 0
                     AND INVENTDIM.INVENTBATCHID LIKE :number

        ;WITH RecursiveConcate
        AS (
            SELECT Batch
                ,CAST(NAMEALIAS AS NVARCHAR(max)) AS NAMEALIAS
                ,CAST(WMSLOCATION AS NVARCHAR(max)) AS WMSLOCATION
                ,CAST(LICENSE AS NVARCHAR(max)) AS LICENSE
                ,2 [rn]
            FROM #Initial AS Initt
            WHERE Initt.rn = 1

            UNION ALL

            SELECT Initt.batch
                ,Initt.NAMEALIAS
                ,IIF(RecCon.WMSLOCATION LIKE '%' + Initt.WMSLOCATION + '%', RecCon.WMSLOCATION, RecCon.WMSLOCATION + ', ' + Initt.WMSLOCATION)
                ,IIF(RecCon.LICENSE LIKE '%' + Initt.LICENSE + '%', RecCon.LICENSE, RecCon.LICENSE + ', ' + Initt.LICENSE)
                ,RecCon.rn + 1
            FROM #Initial AS Initt
            JOIN RecursiveConcate RecCon ON Initt.rn = RecCon.rn
                AND Initt.Batch = RecCon.batch
            )
            ,mRank
        AS (
            SELECT Batch
                ,NAMEALIAS
                ,WMSLOCATION
                ,LICENSE
                ,MAX(rn) OVER (PARTITION BY batch) AS mrn
                ,rn
            FROM RecursiveConcate
            )
        SELECT  BATCH
                ,NAMEALIAS
                ,REPLACE(WMSLOCATION , ' , ', '') as WMSLOCATION
                ,REPLACE(LICENSE, ' , ', '') as LICENSE
        FROM mRank
        WHERE Batch IN (SELECT DISTINCT Batch FROM RecursiveConcate)
              AND rn IN (mrn)
        OPTION (MAXRECURSION 32767)
        DROP TABLE #Initial
        ", [ "number" => $code]);

        if (!$data) {
            $this
                ->telegramNotificationService
                ->sendMessageToTelegram("Партия с номером " . $this->codeNumber  . " не найдена", $this->user->telegram_user_id);
            return;
        }

        try {
            $msg = "";

            foreach ($data as $item) {
                $msg .= $item->BATCH . PHP_EOL;
                $msg .= $item->NAMEALIAS . PHP_EOL;
//                $msg .= $item->COLORID . PHP_EOL;
                $msg .= "Яч: " . $item->WMSLOCATION . PHP_EOL;
                $msg .= "НЗ: " . $item->LICENSE . PHP_EOL;
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
