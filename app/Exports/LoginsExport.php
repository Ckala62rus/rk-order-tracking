<?php

namespace App\Exports;

use App\Http\Resources\Excel\KeyLogger\KeyLoggerResource;
use App\Services\KeyLoggerService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoginsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected array $filter;
    protected KeyLoggerService $keyLoggerService;

    public function __construct(Request $request, KeyLoggerService $keyLoggerService)
    {
        $this->filter = $request->all();
        $this->keyLoggerService = $keyLoggerService;
    }

    public function collection()
    {
        $data = $this
            ->keyLoggerService
            ->getQueryForExportExcel($this->filter);

        return KeyLoggerResource::collection($data);
    }

    public function headings(): array
    {
        return [
            'id',
            'Логин',
            'ФИО',

            'Департамент',
            'Организация',

            'Начала сессии',
            'Дата начала сессии',
            'Время начала сессии',

            'Конец сессии',
            'Дата конца сессии',
            'Время конца сессии',

            'Время работы',
            'Время простоя',

            'Детализация (активные окна)',
        ];
    }
}
