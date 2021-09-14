<?php

namespace App\Exports;

use App\Models\SmsRecord;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class SmsRecordExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting
{
    protected $from_date;
    protected $to_date;
    function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $smss = SmsRecord::whereBetween('created_at', [$this->from_date, $this->to_date])->with(['user.userDetail', 'smsTemplate'])->get();
        return $smss;
    }

    public function map($sms): array
    {
        return [
            $sms->user->username,
            $sms->user->userDetail->name,
            $sms->user->userDetail->fathers_name,
            $sms->number,
            $sms->smsTemplate->sender_id,
            $sms->smsTemplate->message,
            $sms->variables,
            $sms->request_id,
            $sms->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'username',
            'name',
            'fathers name',
            'number',
            'sender_id',
            'template',
            'variables',
            'request_id',
            'created_at',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
