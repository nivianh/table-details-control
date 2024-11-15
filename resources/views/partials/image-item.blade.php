<div style="width: 8rem">
    <a href="{{ RvMedia::getImageUrl($value, null, false, $defaultImage = RvMedia::getDefaultImage()) }}" class="fancybox">
        <x-core::image
            @class(['preview-image', 'default-image' => ! $value])
            data-default="{{ $defaultImage }}"
            src="{{ RvMedia::getImageUrl($value, 'thumb', false, $defaultImage) }}"
            alt="{{ trans('core/base::base.preview_image') }}"
        />
    </a>
</div>
