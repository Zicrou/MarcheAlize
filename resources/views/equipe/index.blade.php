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
                    <table class="table-auto border-separate border-spacing-2 dark:border-gray-500">
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

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    Demandes d'invitation en attente :
                    @if ($invitations->isEmpty())
                        <p>Aucune demande d'invitation en attente.</p>
                    @else
                        <table class="table-auto border-separate border-spacing-2 dark:border-gray-500">
                            <thead>
                                <tr>
                                    <th class="border border-gray-400">Nom</th>
                                <th class="border border-gray-400">Téléphone</th>
                                <th class="border border-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invitations as $invitation)
                                <tr>
                                    <td class="border border-gray-100">{{ $invitation->inviter->name }}</td>
                                    <td class="border border-gray-100">{{ $invitation->inviter->phone }}</td>
                                    <td class="border border-gray-100">
                                        <form action="{{ route('equipe.accepterInvitation', $member->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('post')
                                                <select name="accepterInvitation" id="">
                                                    <option value="">Choisir</option>
                                                    <option value="accepter">Accepter</option>
                                                    <option value="refuser">Refuser</option>
                                                </select>
                                                <div class="" hidden>
                                                    <label for="inviter"></label>
                                                    <input type="text" name="inviter" value="{{ $invitation->inviter->id }}">
                                                </div>

                                                <button type="submit" class="text-blue-500 hover:text-blue-700">Soumettre</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    Vous êtes dans les équipes suivantes :
                    @if ($mesEquipes->isEmpty())
                        <p>Vou n' êtes dans aucune équipe.</p>
                    @else
                        <table class="table-auto border-separate border-spacing-2 dark:border-gray-500">
                            <thead>
                            </thead>
                            <tbody>
                                @foreach ($mesEquipes as $equipe)
                                    <tr>
                                        <td class="border border-gray-100">{{ $equipe->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
