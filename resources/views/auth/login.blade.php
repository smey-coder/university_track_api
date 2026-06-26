<x-guest-layout>
    <!-- Logo -->
    <div class="mb-6 text-center">
        <div class="mb-6 text-center">
            <x-application-logo class="mx-auto w-[180px] h-[180px] object-contain" />
        </div>
                <p class="text-gray-500 mt-2">
                    Sign in to your account
                </p>
                
    </div>
    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 text-green-600 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- <!-- Error Message -->
    @if ($errors->any())
        <div class="mb-4 text-red-600 font-semibold">
            {{ $errors->first() }}
        </div>
    @endif --}}
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="text-end mb-3  mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <div class="d-grid mb-3 mt-4">
                <button type="submit" class="btn btn-primary fw-semibold">
                    Login
                </button>
            </div>
            @if (Route::has('register'))
                <p class="small text-center mb-0">
                    Don’t have an account?
                    <a href="{{route('register')}}" class="fw-semibold text-primary text-decoration-none">
                        Sign Up
                    </a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
