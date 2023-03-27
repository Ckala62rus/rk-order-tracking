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
        return [
            'id' => $this->id,
            'login' => $this->login,
            'fio' => $this->getFio($this->login) ?? $this->login,
            'first_time' => $this->first_time,
            'last_active_time' => $this->last_active_time,
            'time' => Carbon::parse($this->last_active_time)
                ->diff(Carbon::parse($this->first_time))
                ->format('%H:%I:%S'),
            'details' => $this->details
        ];
    }

    /**
     * Get fio from LoginKeyLogger table by domain
     * @param string $login
     * @return mixed
     */
    protected function getFio(string $login)
    {
        $explodeLogin = explode('\\', $login);

        $model =  LoginKeyLogger::where('login', 'LIKE', $explodeLogin[1])->first();
        if (!$model) {
            return null;
        }

        return $model->fio;
    }
}
