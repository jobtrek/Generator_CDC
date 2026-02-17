<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-indigo-300 to-purple-300 rounded-full opacity-10 blur-3xl -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-indigo-300 to-blue-300 rounded-full opacity-10 blur-3xl -ml-48 -mb-48"></div>
        </div>

        <div class="relative w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 mb-4">
                    <span class="text-white font-bold text-2xl">C</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Bienvenue</h1>
                <p class="text-gray-600 dark:text-gray-400">Connectez-vous à votre compte CDC Generator</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form Container -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-slate-800">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Adresse Email')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                        <x-text-input id="email"
                                      class="block mt-2 w-full rounded-lg border-gray-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                      type="email"
                                      name="email"
                                      :value="old('email')"
                                      required
                                      autofocus
                                      autocomplete="username"
                                      placeholder="vous@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                        <x-text-input id="password"
                                      class="block mt-2 w-full rounded-lg border-gray-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                      type="password"
                                      name="password"
                                      required
                                      autocomplete="current-password"
                                      placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me"
                                   type="checkbox"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:bg-slate-800 dark:border-slate-700"
                                   name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Se souvenir de moi') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-medium transition"
                               href="{{ route('password.request') }}">
                                {{ __('Mot de passe oublié?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-8 px-4 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-200 hover:shadow-lg active:scale-95">
                        Se connecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
