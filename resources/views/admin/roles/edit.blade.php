<x-admin-layout
    title="Roles | Dendro Medical"
    :breadcrumbs="[
        [
            'name' => 'Dashboard',
            'href' => route ('admin.dashboard'),
        ],

        [
            'name' => 'Roles',
            'href' => route('admin.admin.roles.index')
        ],
        [
            'name' => 'Editar'
        ]
    ]">

    <x-wire-card>
        <form action="{{ route('admin.admin.roles.update', $role) }}" method="POST">

            @csrf
            @method('PUT')

            <x-wire-input label="Nombre" name="name" placeholder="Nombre del rol" value="{{ old('name' , $role->name) }}">
            </x-wire-input>

            <div class="flex-justify-end mt-4">
                <x-wire-button type='submit' blue>Update</x-wire-button>
            </div>
        </form>
    </x-wire-card>

</x-admin-layout>
