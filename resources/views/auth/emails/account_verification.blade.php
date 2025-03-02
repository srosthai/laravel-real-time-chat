@component('mail::message')
#  Verify your email address

## Dear {{ucfirst($username)}},

#### Please click the below button to activate your account.

@component('mail::button', ['url' => $link])
    Activate Account
@endcomponent

Thanks, <br>
{{ getAppName() }}
@endcomponent
