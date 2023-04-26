<?php

namespace App\Admin\Models\System;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @property string key
 * @property string language
 * @property string subject
 * @property string body
 */
class MailTemplate extends BaseModel
{
    protected $table = 's_mail_templates';

    protected $fillable = [
        'key',
        'language',
        'subject',
        'body',
    ];

    public function __toString()
    {
        $typeName = __('mail.' . $this->key);

        return $this->subject === $typeName
            ? $typeName
            : $this->subject . ' (' . $typeName . ')';
    }
}
