@component('mail::message')
#  Verify your email address

## Dear {{ucfirst($name)}},

<a href="{{(isset($url) && !empty($url)) ? $url : route('password.reset',['token' => $token, 'email' => $email])}}"
class="btn btn-primary">
<strong>{{ __('messages.reset_password') }}</strong>
</a>

Thanks, <br>
{{ getAppName() }}
@endcomponent

