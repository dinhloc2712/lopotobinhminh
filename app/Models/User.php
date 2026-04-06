<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'role_id',
        'phone',
        'province_id',
        'ward_id',
        'street_address',
        'avatar',
        'is_active',
        'code',
        'start_date',
        'branch_id',
        'company_name',
        'tax_code',
        'bank_account',
        'bank_name',
        'matbao_taxcode',
        'matbao_username',
        'matbao_password',
        'matbao_signature_image',
        'mysign_client_id',
        'mysign_client_secret',
        'mysign_profile_id',
        'mysign_user_id',
        'mysign_credential_id',
        'mysign_signature_image',
        'degree',
        'major',
    ];

    /**
     * Check if user has a specific permission via their role
     */
    public function hasPermission($permissionName)
    {
        if (! $this->role) {
            return false;
        }

        // Admin role has all permissions (optional override - mimicking GiaBao)
        if (strtolower($this->role->name) === 'admin') {
            return true;
        }

        return $this->role->permissions()->where('name', $permissionName)->exists();
    }

    /**
     * Check if user has a specific role by name
     */
    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    /**
     * News created by this user
     */
    public function createdNews()
    {
        return $this->hasMany(News::class, 'created_by');
    }

    /**
     * News that this user has read
     */
    public function readNews()
    {
        return $this->hasMany(NewsRead::class, 'user_id');
    }

    /**
     * Get unread news count for this user
     */
    public function unreadNewsCount()
    {
        // We will define this logic either here or in a View Composer / Service.
        // For now we can fetch the count of available news minus read news.
        $readNewsIds = $this->readNews()->pluck('news_id')->toArray();

        return News::where(function ($query) {
            $query->where('recipient_type', 'all')
                ->orWhere(function ($q) {
                    $q->where('recipient_type', 'role')
                        ->whereJsonContains('recipient_ids', (string) $this->role_id);
                })
                ->orWhere(function ($q) {
                    $q->where('recipient_type', 'user')
                        ->whereJsonContains('recipient_ids', (string) $this->id);
                });
        })->whereNotIn('id', $readNewsIds)->count();
    }

    /**
     * Get unread news list for this user
     */
    public function unreadNews($limit = 5)
    {
        $readNewsIds = $this->readNews()->pluck('news_id')->toArray();

        return News::where(function ($query) {
            $query->where('recipient_type', 'all')
                ->orWhere(function ($q) {
                    $q->where('recipient_type', 'role')
                        ->whereJsonContains('recipient_ids', (string) $this->role_id);
                })
                ->orWhere(function ($q) {
                    $q->where('recipient_type', 'user')
                        ->whereJsonContains('recipient_ids', (string) $this->id);
                });
        })->whereNotIn('id', $readNewsIds)->latest()->take($limit)->get();
    }


    /**
     * Chat conversations this user participates in
     */
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

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
        'start_date' => 'date',
        'email_verified_at' => 'datetime',
        'commission_rate'   => 'decimal:2',
        'start_date'        => 'date',
    ];

    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function referralOrders()
    {
        return $this->hasMany(Order::class, 'referrer_id', 'id');
    }

    public function createdPayments(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class, 'created_by');
    }
}
