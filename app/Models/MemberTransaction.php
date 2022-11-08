<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTransaction extends Model
{
    protected $table = 'member_transactions';

    const PAYMENT_CHANNEL_BANK = 'va';
    const PAYMENT_CHANNEL_OVO = 'ovo';
    const PAYMENT_CHANNEL_DANA = 'dana';
    const PAYMENT_CHANNEL_LINKAJA = 'linkaja';


    const PAYMENT_STATUS_PENDING    = 'PENDING';
    const PAYMENT_STATUS_ACCEPT     = 'PAID';
    const PAYMENT_STATUS_EXPIRED    = 'EXPIRED';

    public function bootcamp()
    {
        return $this->hasOne(Bootcamp::class, 'id', 'bootcamp_id');
    }

    public function xendit()
    {
        return $this->hasOne(XenditTransaction::class, 'external_id', 'transaction_id');
    }
}
