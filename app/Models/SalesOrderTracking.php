<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_id',
        'so_product_id',
        'operator_id',
        'sub_product_id',
        'pass_id',
        'unit',
        'item_name',
        'dimensions',
        'operation_id',
        'start_date_time',
        'end_date_time',
        'ideal_cycle_time',
        'total_quantity',
        'quantity_processed',
        'reason',
        'time_taken',
        'roll_status',
        'machine_id',
        'comment',
        'so_pid_primary'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function operation() {
        return $this->hasOne(OperationMaster::class, 'id', 'operation_id');
    }

    public function soProduct() {
        return $this->hasOne(ErpSalesOrder::class, 'so_id', 'so_id');
    }

    public function machine() {
        return $this->hasOne(MachineMaster::class, 'id', 'machine_id');
    }

    public function product() {
        return $this->hasOne(ProductMasters::class, 'id', 'so_product_id');
    }

    public function subProduct() {
        return $this->hasOne(SubProduct::class, 'id', 'sub_product_id');
    }

    public function salesorderProducts() {
        return $this->hasOne(SalesOrderProduct::class, 'so_id', 'so_id');
    }

}
