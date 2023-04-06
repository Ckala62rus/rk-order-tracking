<?php

namespace App\Http\Resources\Excel\KeyLogger;

use App\Models\LoginKeyLogger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyLoggerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $userModel = $this->getModel($this->login);

        return [
            'id' => $this->id,
            'login' => $this->login,
            'fio' => $userModel ? $userModel->fio : $this->login,

            'department' => $userModel ? $userModel->department : 'Отсутствует в 1С',
            'organization' => $userModel ? $userModel->organization : 'Отсутствует в 1С',

            'first_time' => $this->first_time,
            'first_time_date' => Carbon::parse($this->first_time)->format('d.m.Y'),
            'first_time_time' => Carbon::parse($this->first_time)->format('h:i:s'),

            'last_active_time' => $this->last_active_time,
            'last_active_time_date' => Carbon::parse($this->last_active_time)->format('d.m.Y'),
            'last_active_time_time' => Carbon::parse($this->last_active_time)->format('h:i:s'),

            'time' => Carbon::parse($this->last_active_time)
                ->diff(Carbon::parse($this->first_time))
                ->format('%H:%I:%S'),

            'downtime' => $this->downtime ?? '',
//            'details' => $this->details,
            'details' => $this->getDetailsString($this->details),
        ];
    }

    /**
     * Get fio from LoginKeyLogger table by domain
     * @param string $login
     * @return mixed
     */
    protected function getModel(string $login)
    {
        $explodeLogin = explode('\\', $login);

        $model =  LoginKeyLogger::where('login', 'LIKE', $explodeLogin[1])->first();
        if (!$model) {
            return null;
        }

        return $model;
    }

    protected function getDetailsString(Collection $details)
    {
        if (!$details) {
            return '';
        }
        $result = '';

        foreach ($details as $detail) {
            $result .= $detail->active_window . PHP_EOL;
        }

        return $result;
    }
}
