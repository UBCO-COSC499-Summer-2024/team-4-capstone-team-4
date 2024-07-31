<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserRole;
use App\Models\Area;
use App\Models\InstructorPerformance;

class User extends Authenticatable {
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl(){
        $initials = $this->generateInitials($this->firstname, $this->lastname);
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&color=7F9CF5&background=EBF4FF';
    }

    protected function generateInitials($firstname, $lastname){
        $firstInitial = isset($firstname[0]) ? strtoupper($firstname[0]) : '';
        $lastInitial = isset($lastname[0]) ? strtoupper($lastname[0]) : '';
        return $firstInitial . $lastInitial;
    }

    protected static function boot() {
        parent::boot();
        static::created(function ($user) {
            $user->createSettings();
        });
    }

    /**
     * Define a one-to-many relationship with UserRole model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles() {
        return $this->hasMany(UserRole::class, 'user_id');
    }

    public function hasRole($role) {
        return $this->roles->contains('role', $role);
    }

    public function hasRoles($roles = []) {
        return $this->roles->whereIn('role', $roles)->isNotEmpty();
    }

    public function hasOnlyRole($role) {
        return $this->hasRoles([$role]) && $this->roles->count() === 1;
    }

    public function getName() {
        try {
            return $this->firstname . ' ' . $this->lastname;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    public function teaches(){
        return $this->hasManyThrough(Teach::class, UserRole::class, 'user_id', 'instructor_id', 'id', 'id')
                    ->where('user_roles.role', 'instructor');
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function authMethods() {
        return $this->hasMany(AuthMethod::class,'user_id');
    }

    public function instructorPerformances(){
        return $this->hasManyThrough(InstructorPerformance::class, UserRole::class, 'user_id', 'instructor_id', 'id', 'id')
                    ->where('user_roles.role', 'instructor');
    }

    public function settings() {
        return $this->hasOne(Setting::class, 'user_id');
    }

    public function createSettings() {
        return $this->settings()->create();
    }

    public function approvals() {
        return $this->hasMany(Approval::class, 'user_id');
    }

    public function approvalHistories() {
        return $this->hasMany(ApprovalHistory::class, 'user_id');
    }
}
