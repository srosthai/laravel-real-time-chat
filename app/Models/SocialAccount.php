<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class SocialAccount
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|SocialAccount newModelQuery()
 * @method static Builder|SocialAccount newQuery()
 * @method static Builder|SocialAccount query()
 * @method static Builder|SocialAccount whereCreatedAt($value)
 * @method static Builder|SocialAccount whereId($value)
 * @method static Builder|SocialAccount whereProvider($value)
 * @method static Builder|SocialAccount whereProviderId($value)
 * @method static Builder|SocialAccount whereUpdatedAt($value)
 * @method static Builder|SocialAccount whereUserId($value)
 * @mixin Eloquent
 *
 * @property-write mixed $raw
 */
class SocialAccount extends Model
{
    const GOOGLE_PROVIDER = 'google';

    const FACEBOOK_PROVIDER = 'facebook';

    const TWITTER_PROVIDER = 'twitter';

    const YOUTUBE_PROVIDER = 'youtube';

    const SOCIAL_PROVIDERS = [
        self::GOOGLE_PROVIDER,
        self::FACEBOOK_PROVIDER,
        self::TWITTER_PROVIDER,
        self::YOUTUBE_PROVIDER,
    ];

    /**
     * @var string
     */
    protected $table = 'social_accounts';

    /**
     * @var string[]
     */
    protected $fillable = [
        'provider',
        'identifier',
        'device_id',
        'token',
        'token_secret',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'provider' => 'string',
        'identifier' => 'string',
        'device_id' => 'integer',
        'token' => 'string',
        'token_secret' => 'string',
    ];

    public static function facebookFields(): array
    {
        return [
            'first_name',
            'email',
            'gender',
            'id',
            'last_name',
            'name',
            'location',
            'verified',
            'birthday',
            'link',
            'locale',
        ];
    }
}
