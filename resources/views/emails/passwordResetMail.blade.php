<x-mail::message>
    # Reset Password
    To change your password, please click the 'Change Password' button below
    <x-mail::button :url="'http://localhost:3000/response-password-reset' . $token">
        Change Password
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
