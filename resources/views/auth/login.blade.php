<x-guest-layout>
    <!-- Card -->
    <div class="relative overflow-hidden rounded-xl border border-gray-300 bg-white shadow-md">
        <!-- Subtle top accent -->
        <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-indigo-600 via-indigo-400 to-indigo-600"></div>

        <div class="p-8">
            <!-- Brand block -->
            <div class="flex flex-col items-center mb-6">
                <div
                    class="mb-4 inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white p-3 shadow-sm">
                    <x-application-logo class="block h-10 w-auto text-gray-900" />
                </div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                    Welcome back
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Please sign in to your account
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                        autocomplete="username"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between">
                        <x-input-label for="password" :value="__('Password')" />
                    </div>
                    <x-text-input id="password" type="password" name="password" required
                        autocomplete="current-password"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="remember_me" class="ml-2 text-sm text-gray-700">
                        {{ __('Remember me') }}
                    </label>
                </div>

                <!-- Submit -->
                <div>
                    <x-primary-button
                        class="w-full justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold shadow-sm hover:bg-indigo-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- Small legal / footer -->
    <p class="mt-6 text-center text-xs text-gray-500">
        © {{ date('Y') }} Your Company. All rights reserved.
    </p>
    </div>
</x-guest-layout>
