<x-mail::message>
#Reset Password
Reset or change your password.

<x-mail::button :url="'http://localhost:81/response-password-reset?token='.$token">
Change Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
