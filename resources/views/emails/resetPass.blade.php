<form method="POST" action="{{ route('password.update') }}">
    @csrf
    {{-- <input type="hidden" name="token" value="{{ $request->route('token') }}"> --}}
    <input type="hidden" name="token" value="{{ $request->resetToken }}">

    @php dd($request->token) @endphp

    <div>
        <label for="email">{{ __('Email') }}</label>
        {{-- <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autofocus> --}}
        <input id="email" type="email" name="email" value="{{ $request->email }}" required autofocus>

    </div>
    <div>
        <label for="password">{{ __('Password') }}</label>
        <input id="password" type="password" name="password" required>
    </div>
    <div>
        <label for="password-confirm">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" type="password" name="password_confirmation" required>
    </div>
    {{-- @php dd($request->input()) @endphp --}}

    <button type="submit">
        {{ __('Reset Password') }}
    </button>

</form>
