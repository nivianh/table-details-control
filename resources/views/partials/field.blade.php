@php
    use Plugin\TableDetailsControl\Supports\DetailsControlSupport;
@endphp
<div class="mb-3 position-relative overflow-auto">
    <x-core::form.label
        for="show-detail-{{ $model->getKey() }}-{{ $field->getName() }}"
        :label="$field->getOption('label') . ':'"
        @class([
            'd-inline',
        ])
    />
    <div class="d-inline">
        {!! DetailsControlSupport::getItem($model, $field, $key) !!}
    </div>
</div>
