<div class="text-start table-details-wrapper">
    <div class="table-details-control" data-id="{{ $id }}" data-url="{{ $url }}">
        <button type="button" class="btn table-row-expand-icon" data-id="{{ $id }}" data-url="{{ $url }}"></button>
    </div>
    @if ($content)
        <script type="text/x-custom-template" class="table-details-control-main">
            {!! $content !!}
        </script>
    @endif
</div>
