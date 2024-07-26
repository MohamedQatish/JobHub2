<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Mail\Events\MessageSent;
use Laravel\Sanctum\HasApiTokens;

class Company extends Model
{
    use HasFactory, HasApiTokens;

    protected $guard = 'company';
    protected $guarded = [];



    public function isCompany()
    {
        return true;
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    public function routeNotificationForOneSignal(): array
    {
        return ['tags' => ['key' => 'userId', 'relation' => '=', 'value' => (string)($this->id)]];
    }

    public function jobs()
    {
        return $this->hasMany(companyjob::class, 'owner_id');
    }

    // public function sendNewMessageNotification(array $data) : void {
    //     $this->notify(new MessageSent($data));
    // }
    public function photo()
    {
        return $this->morphOne(Photo::class, 'photable');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function rating()
    {
        return $this->hasMany(CompanyRating::class);
    }
    public function ratings()
    {
        return $this->hasMany(CompanyRating::class);
    }

    public function ratingsGiven()
    {
        return $this->morphMany(Ratings::class, 'rater');
    }

    public function ratingsReceived()
    {
        return $this->morphMany(Ratings::class, 'rateable');
    }

    public function bans(){
        return $this->morphMany(Ban::class,'bannable');
    }

    public function banned(){
        return $this->morphOne(Ban::class,'bannable')->latest('banned_at');
    }

    public function reported(){
        return $this->morphMany(Report::class,'reportable');
    }
    
    public function followers()
    {
        return $this->belongsToMany(Freelancer::class, 'company_freelancer', 'company_id', 'freelancer_id');
    }
    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'owner');
    }

    public function contracts()
    {
        return $this->morphMany(Contract::class, 'employer');
    }
}
