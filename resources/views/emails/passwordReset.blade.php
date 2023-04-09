<x-mail::message>
# Reset Password

Reset or change your password.

<form method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div>
        <label for="email">New Password:</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm New Password:</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>
    <div>
        <button type="submit">Reset Password</button>
    </div>
</form>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
