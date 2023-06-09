<div class="card">
    <div class="card-body">
        {{-- <x-evaluate::loading> --}}
            <livewire:evaluate::dashboard.table :department='$department'
                wire:key="department-table-{{ $department?->id ?? null }}" />
            {{--
        </x-evaluate::loading> --}}
    </div>
</div>