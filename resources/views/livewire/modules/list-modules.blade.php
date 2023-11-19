<div>
    <div class="container mx-auto sm:px-6 lg:px-8">
        <x-table.content-table>
            <x-table.actions  :showButtonExport="false">
                <x-slot name="buttonCreate">
                    <livewire:modules.create />
                </x-slot>
            </x-table.actions>
            <x-table>
                <x-table.thead>
                    <x-table.th>MENU</x-table.th>
                    <x-table.th>MODULO/PERMISSÃO</x-table.th>
                    <x-table.th>LABEL</x-table.th>
                    <x-table.th>É UM MÓDULO?</x-table.th>
                    <x-table.th>URL</x-table.th>
                    <x-table.th>ICONE</x-table.th>
                    <x-table.th>ORDER DE EXIBIÇÃO</x-table.th>
                    <x-table.th class="text-right">ACTIONS</x-table.th>
                </x-table.thead>
                <x-table.tbody>
                    @foreach($modules as $module)
                        <x-table.tr>
                            <x-table.th>{{$module->menu_name ?? 'NÃO DEFINIDO'}}</x-table.th>
                            <x-table.th>{{$module->name ?? 'NÃO DEFINIDO'}}</x-table.th>
                            <x-table.th>{{$module->label ?? 'NÃO DEFINIDO'}}</x-table.th>
                            <x-table.th>{{$module->is_module ? 'SIM' : 'NÂO'}}</x-table.th>
                            <x-table.th>{{$module->url ?? 'NÃO DEFINIDO'}}</x-table.th>
                            <x-table.th>{{$module->icon_class ?? 'NÃO DEFINIDO'}}</x-table.th>
                            <x-table.th>{{$module->order_list ?? 'NÃO DEFINIDO'}}</x-table.th>
                            <x-table.th class="flex  justify-end">
                                <livewire:modules.update :module="$module" wire:key="{{now()}}"/>
                                <livewire:modules.delete :module="$module" wire:key="{{now()}}"/>
                            </x-table.th>
                        </x-table.tr>
                    @endforeach
                </x-table.tbody>
            </x-table>
            <div class="p-4"></div>
        </x-table.content-table>
    </div>
</div>
