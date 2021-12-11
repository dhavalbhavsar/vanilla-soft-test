<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    	'email',
        'subject',
        'body'
    ];

    /**
     * Get the email attachmets.
     */
    public function emailAttachments()
    {
        return $this->hasMany(EmailAttachment::class)->select(['id','email_id','original_name']);
    }

}
