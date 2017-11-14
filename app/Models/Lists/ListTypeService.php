<?php

namespace App\Models\Lists;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ListTypeService extends Model
{

  use SoftDeletes;

  protected $dates = ['deleted_at'];

  protected $fillable = ['title'];

  public function quote()
  {
      return $this->hasOne(\App\Models\Quotes\Quote::class);
  }
}
