<div class="d-flex">
    @if ($column->getComponent()->show_button)
    @include('evaluate::tables.show')
    @endif


    @if ($column->getComponent()->edit_button)
    @include('evaluate::tables.edit')
    @endif
</div>