<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Request Account Deletion') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once you submit a request, your account deletion will be queued for review by an administrator. After submitting the request, you will be logged out and your account will be disabled pending approval.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Request Deletion') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Are you sure you want to request account deletion?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Please enter your password and an optional reason to request account deletion. An administrator will review your request.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="reason" value="{{ __('Reason for deletion (optional)') }}" class="text-sm text-gray-700" />
                <textarea
                    id="reason"
                    name="reason"
                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                    rows="3"
                    placeholder="Why do you want to delete your account?"
                ></textarea>
            </div>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Submit Deletion Request') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
