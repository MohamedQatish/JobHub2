<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Mail\Events\MessageSent;
use Laravel\Sanctum\HasApiTokens;

class Company extends Model
{
    use HasFactory,HasApiTokens;

    protected $guard = 'company';
    protected $guarded = [];

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    public function routeNotificationForOneSignal() : array{
        return ['tags'=>['key'=>'userId','relation'=>'=', 'value'=>(string)($this->id)]];
    }

    // public function sendNewMessageNotification(array $data) : void {
    //     $this->notify(new MessageSent($data));
    // }
    public function photo(){
        return $this->morphOne(Photo::class,'photable');
    }

}
