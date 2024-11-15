@foreach ($form->getFields() as $key => $field)
    @if (in_array($key, $columns))
        @php
            $value = $model->translations->where('lang_code', $locale)->value($key);
        @endphp
        @include('plugins/table-details-control::partials.field')
    @endif
@endforeach
