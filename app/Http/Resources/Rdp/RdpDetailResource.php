<?php

namespace App\Http\Resources\Rdp;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed Event_run    // shell start начало работы disconnect окончание
 * @property mixed Date_run     // день начала работы
 * @property mixed DayTime_run  // время начала работы
 * @property mixed WorkMinute   // время работы в минутах
 * @property mixed DayTime_end  // время окончания работы
 * @property mixed Date_end     // день окончания работы
 * @property mixed Event_end    // окончание работы
 */
class RdpDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'event_run' => $this->getStatus($this->Event_run),
            'date_run' => Carbon::parse($this->Date_run)->format('Y-m-d'),
            'day_time_run' => Carbon::parse($this->DayTime_run)->format('H:i:s'),
            'event_end' => $this->getStatus($this->Event_end),
            'date_end' => Carbon::parse($this->Date_end)->format('Y-m-d'),
            'day_time_end' => Carbon::parse($this->DayTime_end)->format('H:i:s'),
            'work_minute' => $this->WorkMinute, // время работы в минутах
        ];
    }

    /**
     * Translate event status
     * @param string $status
     * @return string
     */
    private function getStatus(string $status): string
    {
         switch ($status)
         {
             case 'Shell Start':
                 return 'Подключено';
             case 'Disconnected':
                 return 'Отсоеденено';
             case 'Reconnection':
                 return 'Переподключение';
             default:
                 return $status;
         }
    }
}
