<div>
    @php

    $title = $editing ? join(" ", [ __('Edit'), __('Department'), $department->name]) : join(" ", [__('Create'),
    __('New'), __('Department') ])
    @endphp

    <x-evaluate::modal modal-name="DepartmentForm" title="{{ $title }}" event-name="{{ $this->modal_event_name_form }}"
        :backdrop="false">

        <x-evaluate::form :editing="$editing">
            <div class="p-3">
                <x-evaluate::inputs.with-labels field="department.name">{{ str( __('evaluate::messages.name'))->headline()
                    }}:
                </x-evaluate::inputs.with-labels>

                <x-evaluate::inputs.text-area field="department.description" :required="false">
                    {{ str(__('evaluate::messages.description'))->headline() }}:
                </x-evaluate::inputs.text-area>
            </div>
        </x-evaluate::form>
    </x-evaluate::modal>
</div>