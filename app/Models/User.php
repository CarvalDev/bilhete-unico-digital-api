<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUsers(string $search = ''){
        $users =  $this->where(function ($query) use ($search){
            if($search){
               $query->where('email', $search);
               $query->orWhere('name', 'LIKE', "%$search%");
   
            }
         })
         ->with('comments')
         ->paginate(15);
         return $users;
    }

    public function updateUsers($request){
        $data = $request->only('name', 'email');
      if($request->password)
         $data['password'] = bcrypt($request->password);
        return $data;
    }

    
    //metodo que retorna todos os comnentarios de certo usuario
}
