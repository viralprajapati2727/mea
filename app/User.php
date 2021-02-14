<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\SendMailController;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Laravelista\Comments\Commenter;
class User extends Authenticatable implements MustVerifyEmail, ShouldQueue
{
    use Notifiable, Sluggable, Commenter;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'is_active' => 1,
        ])->save();
    }

    public function sendEmailVerificationNotification(){
        $verificationUrl = $this->verificationUrl($this);
        SendMailController::dynamicEmail([
            'email_id' => 1,
            'user_id' => $this->id,
            'verificationUrl' => $verificationUrl
        ]);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {

        $verificationUrl = url(config('app.url').route('password.reset', ['token' => $token, 'email' => $this->email], false));
        SendMailController::dynamicEmail([
            'email_id' => 3,
            'user_id' => $this->id,
            'verificationUrl' => $verificationUrl
        ]);
    }

    /**
     * @inheritDoc
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function userProfile(){
        return $this->hasOne('App\Models\UserProfile', 'user_id');
    }
    public function answers(){
        return $this->hasMany('App\Models\UserAnswer', 'user_id');
    }
    public function interests(){
        return $this->hasMany('App\Models\UserInterest', 'user_id');
    }
    public function skills(){
        return $this->hasMany('App\Models\UserSkill', 'user_id');
    }
    public function educationDetails(){
        return $this->hasMany('App\Models\UserEducationDetail', 'user_id');
    }
    public function workExperience(){
        return $this->hasMany('App\Models\UserWorkExperience', 'user_id');
    }
    public function members(){
        return $this->hasMany('App\Models\ChatMasters', 'user_id', 'id');
    }
}
