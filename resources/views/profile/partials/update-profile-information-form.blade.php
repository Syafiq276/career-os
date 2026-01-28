<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            <p class="mt-1 text-xs text-gray-500">Brief description about yourself for your public portfolio.</p>
        </div>

        <!-- Skills -->
        <div>
            <x-input-label for="skills" :value="__('Skills')" />
            <x-text-input id="skills" name="skills" type="text" class="mt-1 block w-full" :value="old('skills', is_array($user->skills) ? implode(', ', $user->skills) : '')" />
            <x-input-error class="mt-2" :messages="$errors->get('skills')" />
            <p class="mt-1 text-xs text-gray-500">Comma-separated list (e.g., Laravel, PHP, React, TailwindCSS)</p>
        </div>

        <!-- GitHub Username -->
        <div>
            <x-input-label for="github_username" :value="__('GitHub Username')" />
            <x-text-input id="github_username" name="github_username" type="text" class="mt-1 block w-full" :value="old('github_username', $user->github_username)" />
            <x-input-error class="mt-2" :messages="$errors->get('github_username')" />
            <p class="mt-1 text-xs text-gray-500">Your GitHub username (without @)</p>
        </div>

        <!-- LinkedIn URL -->
        <div>
            <x-input-label for="linkedin_url" :value="__('LinkedIn URL')" />
            <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" :value="old('linkedin_url', $user->linkedin_url)" />
            <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
        </div>

        <!-- Portfolio URL -->
        <div>
            <x-input-label for="portfolio_url" :value="__('Portfolio URL')" />
            <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="mt-1 block w-full" :value="old('portfolio_url', $user->portfolio_url)" />
            <x-input-error class="mt-2" :messages="$errors->get('portfolio_url')" />
        </div>

        <!-- Public Profile Toggle -->
        <div class="flex items-center">
            <input id="is_profile_public" name="is_profile_public" type="checkbox" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_profile_public', $user->is_profile_public) ? 'checked' : '' }}>
            <label for="is_profile_public" class="ml-2 block text-sm text-gray-900">
                Make my portfolio public
            </label>
        </div>
        @if($user->is_profile_public)
            <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                <p class="text-sm text-blue-800">
                    🌐 Your portfolio is public at: 
                    <a href="{{ route('portfolio.show', $user->name) }}" target="_blank" class="font-semibold underline hover:text-blue-600">
                        {{ route('portfolio.show', $user->name) }}
                    </a>
                </p>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
