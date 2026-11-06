<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Ajouter un membre') }}
        </h2>
    </header>

    <form method="post" action="{{ route('equipe.invite') }}" class="mt-6 space-y-6">
        @csrf
        <div>
            <x-input-label for="ajouter_un_membre" :value="__('Ajouter un membre')" />
            <x-text-input id="ajouter_un_membre" name="membre" type="text" :value="old('membre')" class="mt-1 block w-full" required autofocus autocomplete="membre" />
            <x-input-error class="mt-2" :messages="$errors->get('membre')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'membre-ajouté')
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
