<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Membres de l\'équipe') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('equipe.partials.ajout-membre')
                </div>
            </div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    Liste des membres de l'équipe :
                    <table class="table-auto border-separate border-spacing-2 dark:border-gray-500 w-full mt-4">
                        <thead>
                            <tr>
                                <th class="border border-gray-400">Nom</th>
                                <th class="border border-gray-400">Email</th>
                                <th class="border border-gray-400">Téléphone</th>
                                <th class="border border-gray-400">Rôle</th>
                                <th class="border border-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($liste_membres as $member)
                                <tr>
                                    <td class="border border-gray-100">{{ $member->name }}</td>
                                    <td class="border border-gray-100">{{ $member->email }}</td>
                                    <td class="border border-gray-100">{{ $member->phone }}</td>
                                    <td class="border border-gray-100">{{ $member->pivot->role }}</td>
                                    <td class="border border-gray-100">
                                        <form action="{{ route('equipe.destroy', $member->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            @if ($member->pivot->role === 'admin')
                                                <span class="text-gray-500">Admin</span>
                                            @else
                                                <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
