<?php

namespace App\Http\Resources\KeyLogger;

use App\Models\LoginKeyLogger;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyLoggerIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $userModel = $this->getModel($this->login);

        return [
            'id' => $this->id,
            'login' => $this->login,
            'fio' => $userModel ? $userModel->fio : $this->login,
            'first_time' => Carbon::parse($this->first_time)->format('y-m-d H:i:s'),
            'last_active_time' => $this->last_active_time,
            'time' => Carbon::parse($this->last_active_time)
                ->diff(Carbon::parse($this->first_time))
                ->format('%H:%I:%S'),
            'details' => $this->details,
            'department' => $userModel ? $userModel->department : 'Отсутствует в 1С',
            'organization' => $userModel ? $userModel->organization : 'Отсутствует в 1С',
            'downtime' => $this->downtime ?? '',
            'activeWindowsSeconds' => $this->activeWindowsSeconds
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
}
