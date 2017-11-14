<?php

namespace App\Models\Lists;

use Illuminate\Database\Eloquent\Model;

class ListStatusQuote extends Model
{
  protected $fillable = ['title'];

  public function quote()
  {
      return $this->hasOne(\App\Models\Quotes\Quote::class);
  }
}
