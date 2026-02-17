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
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Créer un nouveau mot de passe</h1>
                <p class="text-gray-600 dark:text-gray-400">Entrez votre email et définissez un nouveau mot de passe sécurisé</p>
            </div>

            <!-- Form Container -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-xl p-8 border border-gray-200 dark:border-slate-800">
                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Adresse Email')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                        <x-text-input id="email"
                                      class="block mt-2 w-full rounded-lg border-gray-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                      type="email"
                                      name="email"
                                      :value="old('email', $request->email)"
                                      required
                                      autofocus
                                      autocomplete="username"
                                      placeholder="vous@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Nouveau mot de passe')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                        <x-text-input id="password"
                                      class="block mt-2 w-full rounded-lg border-gray-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                      type="password"
                                      name="password"
                                      required
                                      autocomplete="new-password"
                                      placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Minimum 8 caractères</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                        <x-text-input id="password_confirmation"
                                      class="block mt-2 w-full rounded-lg border-gray-300 dark:bg-slate-800 dark:border-slate-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                                      type="password"
                                      name="password_confirmation"
                                      required
                                      autocomplete="new-password"
                                      placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Help Text -->
                    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <span class="font-semibold">Conseil de sécurité:</span> Utilisez un mot de passe fort avec majuscules, minuscules, chiffres et caractères spéciaux.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full mt-8 px-4 py-3 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold transition-all duration-200 hover:shadow-lg active:scale-95">
                        Réinitialiser le mot de passe
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-slate-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white dark:bg-slate-900 text-gray-600 dark:text-gray-400">Vous vous souvenez?</span>
                    </div>
                </div>

                <!-- Back to Login -->
                <a href="{{ route('login') }}"
                   class="w-full block text-center px-4 py-3 rounded-lg border-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 font-semibold hover:bg-indigo-50 dark:hover:bg-slate-800 transition-all duration-200">
                    Retour à la connexion
                </a>
            </div>

            <!-- Footer Text -->
            <p class="text-center text-xs text-gray-600 dark:text-gray-400 mt-8">
                Besoin d'aide?
                <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Contactez notre support</a>
            </p>
        </div>
    </div>
</x-guest-layout>
