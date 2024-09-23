<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
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
            <x-input-label for="name" :value="__('Team Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="sponsor" :value="__('Sponsor')" />
            <x-text-input id="sponsor" name="sponsor" type="text" class="mt-1 block w-full" :value="old('sponsor', $user->sponsor)" required autofocus autocomplete="sponsor" />
            <x-input-error class="mt-2" :messages="$errors->get('sponsor')" />
        </div>

        <div>
            <x-input-label for="ign" :value="__('IGN')" />
            <x-text-input id="ign" name="ign" type="text" class="mt-1 block w-full" :value="old('ign', $user->ign)" required autofocus autocomplete="ign" />
            <x-input-error class="mt-2" :messages="$errors->get('ign')" />
        </div>

        <div>
            <x-input-label for="dob" :value="__('Date of Birth')" />
            <x-text-input id="dob" name="dob" type="date" class="mt-1 block w-full" :value="old('dob', $user->dob)" required autofocus autocomplete="dob" />
            <x-input-error class="mt-2" :messages="$errors->get('dob')" />
        </div>

        <div>
            <x-input-label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="rank" :value="__('Rank')" />
            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="rank" name="rank" class="text-gray-900 mt-1 block w-full" required autofocus autocomplete="rank">
                <option value="Gold" {{ old('rank', $user->rank) == 'Gold' ? 'selected' : '' }}>Gold</option>
                <option value="Diamond" {{ old('rank', $user->rank) == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                <option value="Ascendant" {{ old('rank', $user->rank) == 'Ascendant' ? 'selected' : '' }}>Ascendant</option>
                <option value="Immortal" {{ old('rank', $user->rank) == 'Immortal' ? 'selected' : '' }}>Immortal</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('rank')" />
        </div>


        <div>
            <x-input-label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="roles" :value="__('Roles')" />
            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="roles" name="roles" class="text-gray-900 mt-1 block w-full" required autofocus autocomplete="roles">
                <option value="Duelists" {{ old('roles', $user->roles) == 'Duelists' ? 'selected' : '' }}>Duelists</option>
                <option value="Sentinel" {{ old('roles', $user->roles) == 'Sentinel' ? 'selected' : '' }}>Sentinel</option>
                <option value="Initiator" {{ old('roles', $user->roles) == 'Initiator' ? 'selected' : '' }}>Initiator</option>
                <option value="Controller" {{ old('roles', $user->roles) == 'Controller' ? 'selected' : '' }}>Controller</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('roles')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
