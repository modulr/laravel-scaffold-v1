<?php

namespace App\Models\Quotes;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lists\ListStatusQuote;
use App\Models\Lists\ListTypeService;
use App\Models\Lists\ListTypeCurrency;
use Illuminate\Database\Eloquent\SoftDeletes;
class Quote extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description', 'owner_id', 'designer_id', 'salesman_id', 'project_id', 'customer_id',
                          'request_date','delivery_date','close_date','amount','status_id', 'service_id', 'currency_id'];

    public function designer()
    {
        return $this->belongsTo(Employee::class, 'designer_id');
    }

    public function salesman()
    {
        return $this->belongsTo(Employee::class, 'salesman_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attachment()
    {
      return $this->hasMany(Attachment::class);
    }

    public function status()
    {
        return $this->belongsTo(ListStatusQuote::class);
    }

    public function service()
    {
        return $this->belongsTo(ListTypeService::class);
    }

    public function currency()
    {
        return $this->belongsTo(ListTypeCurrency::class);
    }

}
