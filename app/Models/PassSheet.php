<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model; 

class PassSheet extends Model
{
   
    protected $table = 'pass_sheets'; // Optional if using default naming

    protected $fillable = [
        'so_id',
        'subproduct_id',
        'cpoitemid',
        'sr_no',
        'pass_no',
        'mrk_pass_no',
        'drawing_no',
        'size1',
        'size2',
        'size3',
        'qty',
        'material',
        'hardness',
        'fin_wt',
        'bs1_dia',
        'bs1_depth',
        'bs1_bore',
        'bs2_dia',
        'bs2_depth',
        'remarks',
        'revisioncount',
        'pass_sheet_qr_code'
    ];
 
    public function getPassSheetQrCodeAttribute($value)
    {
        return $value ? asset('storage/so-pass-sheet-qrcodes/' . $value) : asset(DEFAULT_QR);
    }

}
