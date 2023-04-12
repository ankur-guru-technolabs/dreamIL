<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        //'email',
        'status',
        'phone',
        'gender',
        'profile_image',
        'login_otp',
        'city',
        'country',
        'latitude',
        'longitude',
        'password',
        'api_token',
        'category',
        'email_verified',
    ];

    public function getProfileImageAttribute($value)
    {
        return !empty($value) ? url('images')."/".$value : '';
    }


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getPassionAttribute($value)
    {
        return !empty($value) ? unserialize($value) : [];
    }

    public function userKids()
    {
        return $this->hasMany('App\Models\UserKids', 'user_id','id');
    }

    public function userQuestion()
    {
        return $this->hasMany('App\Models\UserQuestions', 'user_id','id');
    }

    public function userImages()
    {
        return $this->hasMany('App\Models\UserImages', 'user_id','id');
    }

    public function userSettings()
    {
        return $this->hasOne('App\Models\UserSettings', 'user_id','id');
    }

    public function Orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id','id');
    }

    public function categories()
    {
        return $this->belongsTo('App\Models\Categories', 'category','id');
    }

    public function generalQuestionAnswer()
    {
        return $this->hasMany('App\Models\GeneralQuestionAnswer', 'user_id','id');
    }


    protected function addUpdateUser($params = [])
    {
        if(empty($params)) {
            return false;   
        }

        $id = Arr::get($params, 'id', 0);
        
        if($id > 0) {
            $model = self::find($id);
            if(!$model) {
                $model = new self();
            }
        } else {
            $model = new self();
        }

        $params        = Arr::except($params, ['id']);
        $fillableFiled = $model->getFillable();
        $fillableFiled = array_intersect_key($params, array_flip($fillableFiled));
        $model->fill($fillableFiled);
        $result = $model->save();
        if($result) {
            return $model;
        }

        return false;
    }
}
