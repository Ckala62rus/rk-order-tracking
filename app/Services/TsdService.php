<?php

namespace App\Services;

use App\Sql\SqlScripts;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class TsdService
{

    /**
     * Command execute router
     * @param string $code
     * @return array
     */
    public function getRouteCommand(string $code): array
    {

        if ( preg_match('/^[0-9]{1,2}[%]{1}[0-9]{4,5}$/', $code, $single_command) ) {
            return $this->executeCommandFindCell2($code);
        }

        if ( preg_match('/^([0-9]{1,2}[%]{1}[0-9]{4,5})[*]$/', $code, $single_command) ) {
            return $this->executeCommandFindCellWithColor($single_command[1]);
        }

        if ( preg_match('/^[0-9]{4,5}$/', $code, $single_command) ) {
            return $this->executeCommandFindCellByOnlyNumber($code);
        }

        if ( preg_match('/^([0-9]{4,5})[*]$/', $code, $single_command) ) {
            return $this->executeCommandFIndCellByOnlyNumberWithColor($single_command[1]);
        }

        if ( preg_match('/^([0-9]{1,2}[%]{1}[0-9]{4,10})[@]$/', $code, $single_command) ) {
            return $this->executeCommandFindCellWithColorAndUser($single_command[1]);
        }

        if ( preg_match('/^([0-9]{4,10})[@]$/', $code, $code) ) {
            return $this->executeCommandFindCellByOnlyNumberWithUser($code[1]);
        }

        return ['error' => 'Введена неверная команда'];
    }

    /**
     * Find part by mask 22%12345
     * @param string $code
     * @return array
     */
    private function executeCommandFindCell2(string $code): array
    {
        $data = DB::connection("dax")->select(SqlScripts::getSqlQuery(), [ "number" => $code]);

        $isExistPart = $this->partNotFound($data);

        if (!$isExistPart) {
            return ['error' => 'Партия не найдена'];
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

            return ['result' => $msg];

        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    /**
     * Find part by mask 22%3211*
     * @param string $code
     * @return array
     */
    private function executeCommandFindCellWithColor(string $code): array
    {
        $data = DB::connection("dax")->select(SqlScripts::getSqlQueryWithColor(), ["number" => $code]);

        $isExistPart = $this->partNotFound($data);

        if (!$isExistPart) {
            return ['error' => 'Партия не найдена'];
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

            return ['result' => $msg];

        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    /**
     * Find part by mask 3211
     * @param string $code
     * @return array
     */
    private function executeCommandFindCellByOnlyNumber(string $code): array
    {
        return $this->executeCommandFindCell2($this->getCurrentYear() . '%' . $code);
    }

    /**
     * Find part by mask 3211*
     * @param string $code
     * @return array
     */
    private function executeCommandFIndCellByOnlyNumberWithColor(string $code): array
    {
        return $this->executeCommandFindCellWithColor($this->getCurrentYear() . '%' . $code);
    }

    /**
     * Find part by mask 22%3211@
     * @param string $code
     * @return array
     */
    private function executeCommandFindCellWithColorAndUser(string $code): array
    {

        $data = DB::connection("dax")->select(SqlScripts::getSqlQueryByPartialWithUsers(), ["number" => $code]);

        $isExistPart = $this->partNotFound($data);

        if (!$isExistPart) {
            return ['error' => 'Партия не найдена'];
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

            return ['result' => $msg];

        } catch (Exception $ex) {
            return ['error' => $ex->getMessage()];
        }
    }

    /**
     * Find part by mask 3211@
     * @param string $code
     * @return array
     */
    private function executeCommandFindCellByOnlyNumberWithUser(string $code): array
    {
        return $this->executeCommandFindCellWithColorAndUser($this->getCurrentYear() . '%' . $code);
    }

    /**
     * Check, exist part or not
     * @param array $data
     * @return bool
     */
    public function partNotFound(array $data): bool
    {
        if (!$data) {
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
}
