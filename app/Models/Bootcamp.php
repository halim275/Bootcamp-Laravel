<?php

namespace App\Models;

use App\Models\MemberTransaction;
use Illuminate\Database\Eloquent\Model;

class Bootcamp extends Model
{
    protected $table = 'bootcamps';

    public function member()
    {
        return $this->belongsTo(MemberTransaction::class);
    }
}
