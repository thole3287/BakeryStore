<x-mail::message>
#Reset Password
Reset or change your password.

<a href="{{ url('response-password-reset', ['token' => $token]) }}">Reset password</a>


{{-- <a href="http://localhost:81/response-password-reset?token=<?php echo $token ?>">Change Password</a> --}}
{{-- <x-mail::button :url="'http://localhost:81/response-password-reset?token='.$token">
Change Password
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
