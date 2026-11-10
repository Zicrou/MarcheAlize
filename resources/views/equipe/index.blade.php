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
                <div class="max-w-full">
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Membres de l'équipe</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Consultez les informations et gérez les accès des membres.</p>
                    </div>
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Nom</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Téléphone</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Rôle</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300 border">Journal</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                            <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($liste_membres as $member)
                                <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $member->email }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $member->phone }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $member->pivot->role === 'admin' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                            {{ $member->pivot->role }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-sm border">
                                        
                                                @if ($member->pivot->role !== 'admin')
                                                    <input name="coequipier" value="{{ $member->id }}" hidden>
                                                    <a href="{{ route('admin.journal.depenses', ["coequipier" => $member->id]) }}" type="submit" class="rounded-md bg-blue-50 px-3 py-1.5 font-medium text-blue-700 transition hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50">Dépense</a>
                                                    <a href="{{ route('admin.journal.operations', ["coequipier" => $member->id]) }}"  type="submit" class="rounded-md bg-indigo-50 px-3 py-1.5 font-medium text-indigo-700 transition hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50">Opération</button>
                                                @endif
                                           
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-sm">
                                        <form action="{{ route('equipe.destroy', $member->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce user ?');">
                                            @csrf
                                            @method('DELETE')
                                            @if ($member->pivot->role !== 'admin')
                                                <button type="submit" class="rounded-md bg-red-50 px-3 py-1.5 font-medium text-red-700 transition hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50">Supprimer</button>
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
                                    <td class="border border-gray-100">Journal</td>
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
