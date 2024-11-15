{!! do_action('table_details_control_before_show', $model, $form) !!}

<x-core::card>
    <x-core::card.header>
        <x-core::tab class="card-header-tabs">
            <x-core::tab.item
                :is-active="true"
                id="{{ 'detail-' . $model->getKey() }}"
                icon="ti ti-info-circle"
                label="{{ 'Details' }}"
            />

            {!! apply_filters('table_details_control_register_tabs', null, $model, $form) !!}
        </x-core::tab>
    </x-core::card.header>

    <x-core::card.body>
        <x-core::tab.content>
            <x-core::tab.pane
                id="{{ 'detail-' . $model->getKey() }}"
                :is-active="true"
            >
                @foreach ($form->getFields() as $key => $field)
                    @include('plugins/table-details-control::partials.field')
                @endforeach
            </x-core::tab.pane>

            {!! apply_filters('table_details_control_register_contents', null, $model, $form) !!}
        </x-core::tab.content>
    </x-core::card.body>
</x-core::card>
