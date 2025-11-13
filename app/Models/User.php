<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'address',
        'role',
        'birth',
        'is_tdc_student',
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
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth' => 'date',
        ];
    }

    // Quan hệ 1-n với CartItem
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id', 'user_id');
    }
    // Hàm trả về số lượng items trong giỏ hàng
    public function cartItemsCount()
    {
        return $this->cartItems()->sum('quantity');
    }

    // Quan hệ 1-1 với UserProfile
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'user_id');
    }
}
