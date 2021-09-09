<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailForEmailNewsletter extends Model
{
    protected $table = 'emails_for_email_newsletter';

    protected $guarded = [];

    public function group()
    {
        return $this->hasOne(EmailGroup::class, 'id', 'group_id');
    }
}
