<?php

namespace App\Models;

use App\Traits\ImageTrait;
use Auth;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $phone
 * @property string|null $last_seen
 * @property string|null $about
 * @property string $photo_url
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[]
 *     $notifications
 * @property-read int|null $notifications_count
 *
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAbout($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastSeen($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User wherePhotoUrl($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @mixin Eloquent
 *
 * @property-read Collection|Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property int|null $is_online
 * @property string|null $activation_code
 *
 * @method static Builder|User whereActivationCode($value)
 * @method static Builder|User whereIsOnline($value)
 *
 * @property int|null $is_active
 *
 * @method static Builder|User whereIsActive($value)
 *
 * @property-read Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 *
 * @method static Builder|User permission($permissions)
 * @method static Builder|User role($roles, $guard = null)
 *
 * @property-read string $role_id
 * @property-read string $role_name
 * @property int|null $is_system
 *
 * @method static Builder|User whereIsSystem($value)
 *
 * @property string|null $player_id One signal user id
 * @property int|null $is_subscribed
 *
 * @method static Builder|User whereIsSubscribed($value)
 * @method static Builder|User wherePlayerId($value)
 *
 * @property-read Collection|BlockedUser[] $blockedBy
 * @property-read Collection|UserDevice[] $devices
 * @property-read int|null $blocked_by_count
 * @property-write mixed $raw
 * @property int $privacy
 * @property int|null $gender
 * @property-read UserStatus $userStatus
 *
 * @method static Builder|User whereGender($value)
 * @method static Builder|User wherePrivacy($value)
 *
 * @property int $is_default
 * @property Carbon|null $deleted_at
 * @property-read int|null $devices_count
 * @property-read ReportedUser|null $reportedUser
 *
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereIsDefault($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable, ImageTrait, HasApiTokens, HasRoles, SoftDeletes, Impersonate;
    use ImageTrait {
        deleteImage as traitDeleteImage;
    }

    const BLOCK_UNBLOCK_EVENT = 1;

    const NEW_PRIVATE_CONVERSATION = 2;

    const ADDED_TO_GROUP = 3;

    const PRIVATE_MESSAGE_READ = 4;

    const MESSAGE_DELETED = 5;

    const MESSAGE_NOTIFICATION = 6;

    const CHAT_REQUEST = 7;

    const CHAT_REQUEST_ACCEPTED = 8;

    const PROFILE_UPDATES = 1;

    const STATUS_UPDATE = 2;

    const STATUS_CLEAR = 3;

    const FILTER_UNARCHIVE = 1;

    const FILTER_ARCHIVE = 2;

    const FILTER_ACTIVE = 3;

    const FILTER_INACTIVE = 4;

    const FILTER_ALL = 5;

    const PRIVACY_FILTER_PUBLIC = 1;

    const PRIVACY_FILTER_PRIVATE = 0;

    const FILTER_ARRAY = [
        self::FILTER_UNARCHIVE => 'Unarchive',
        self::FILTER_ARCHIVE => 'Archive',
        self::FILTER_ACTIVE => 'Active',
        self::FILTER_INACTIVE => 'Inactive',
    ];

    const PRIVACY_FILTER_ARRAY = [
        self::PRIVACY_FILTER_PUBLIC => 'Public',
        self::PRIVACY_FILTER_PRIVATE => 'Private',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'last_seen',
        'is_online',
        'about',
        'photo_url',
        'activation_code',
        'is_active',
        'is_system',
        'email_verified_at',
        'player_id',
        'is_subscribed',
        'gender',
        'privacy',
        'language',
        'is_super_admin',
    ];

    const LANGUAGES = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'ru' => 'Russian',
        'pt' => 'Portuguese',
        'ar' => 'Arabic',
        'zh' => 'Chinese',
        'tr' => 'Turkish',
        'it' => 'Italian',
    ];

    public static $PATH = 'users';

    const HEIGHT = 250;

    const WIDTH = 250;

    const MALE = 1;

    const FEMALE = 2;

    /**
     * @var string[]
     */
    protected $with = ['roles'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'role_name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'phone' => 'string',
        'last_seen' => 'datetime',
        'is_online' => 'boolean',
        'about' => 'string',
        'photo_url' => 'string',
        'activation_code' => 'string',
        'is_active' => 'integer',
        'is_system' => 'boolean',
        'email_verified_at' => 'datetime',
        'player_id'         => 'string',
        'is_subscribed'     => 'boolean',
        'gender'            => 'integer',
        'privacy'           => 'integer',
        'language'          => 'string',
        'is_super_admin'    => 'boolean',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:100',
        'phone' => 'nullable|integer',
        'role_id' => 'required|integer',
        'privacy' => 'required',
        'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
    ];

    public static $messages = [
        'phone.integer' => 'Please enter valid phone number',
        'phone.digits' => 'The phone number must be 10 digits long',
        'email.regex' => 'Please enter valid email',
        'role_id.required' => 'Please select user role',
    ];

    /**
     * @param $value
     * @return string
     */
    public function getPhotoUrlAttribute($value)
    {
        if (! empty($value)) {
            return $this->imageUrl(self::$PATH.DIRECTORY_SEPARATOR.$value);
        }

        if ($this->gender == self::MALE) {
            return asset('assets/icons/male.png');
        }
        if ($this->gender == self::FEMALE) {
            return asset('assets/icons/female.png');
        }

        return getUserImageInitial($this->id, $this->name);
    }

    /**
     * @return string
     */
    public function getRoleNameAttribute()
    {
        $userRoles = $this->roles->first();

        return (! empty($userRoles)) ? $userRoles->name : '';
    }

    /**
     * @return string
     */
    public function getRoleIdAttribute()
    {
        $userRoles = $this->roles->first();

        return (! empty($userRoles)) ? $userRoles->id : '';
    }

    /**
     * @return array
     */
    public function webObj()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'last_seen' => $this->last_seen,
            'about' => $this->about,
            'photo_url' => $this->photo_url,
            'gender' => $this->gender,
            'privacy' => $this->privacy,
        ];
    }

    /**
     * @return array
     */
    public function apiObj()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => (! empty($this->email_verified_at)) ? $this->email_verified_at->toDateTimeString() : '',
            'phone' => $this->phone,
            'last_seen' => $this->last_seen,
            'is_online' => $this->is_online,
            'is_active' => $this->is_active,
            'gender' => $this->gender,
            'about' => $this->about,
            'photo_url' => $this->photo_url,
            'activation_code' => $this->activation_code,
            'created_at' => (! empty($this->created_at)) ? $this->created_at->toDateTimeString() : '',
            'updated_at' => (! empty($this->updated_at)) ? $this->updated_at->toDateTimeString() : '',
            'is_system' => $this->is_system,
            'role_name' => (! $this->roles->isEmpty()) ? $this->roles->first()->name : null,
            'role_id' => (! $this->roles->isEmpty()) ? $this->roles->first()->id : null,
            'privacy' => $this->privacy,
            'archive' => (! empty($this->deleted_at)) ? 1 : 0,
        ];
    }

    /**
     * @return bool
     */
    public function deleteImage()
    {
        $image = $this->getRawOriginal('photo_url');
        if (empty($image)) {
            return true;
        }
        $this->update(['photo_url' => null]);

        return $this->traitDeleteImage(self::$PATH.DIRECTORY_SEPARATOR.$image);
    }

    /**
     * @return HasMany
     */
    public function blockedBy()
    {
        return $this->hasMany(BlockedUser::class, 'blocked_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }

    /**
     * @return HasOne
     */
    public function userStatus()
    {
        return $this->hasOne(UserStatus::class);
    }

    /**
     * @return HasOne
     */
    public function reportedUser()
    {
        return $this->hasOne(ReportedUser::class, 'reported_to')->where('reported_by', '=', Auth::id());
    }
}
