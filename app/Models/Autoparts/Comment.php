<?php

namespace App\Models\Autoparts;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];

    public $table = 'comments_autoparts';

    protected $fillable = [
        'comment', 'autopart_id'
    ];

    public function autopart()
    {
        return $this->belongsTo(Autopart::class);
    }
}
