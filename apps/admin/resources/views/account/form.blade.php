<form method="post" data-title="{{ $title }}" class="settings-form {{ $cls ?? '' }}">
    {!! isset($description) ? '<p>' . $description . '</p>' : '' !!}

    <div class="fields-wrap">
        {!! $form !!}
    </div>

    <div class="form-buttons">
        <button type="submit" class="btn btn-primary">@lang('Сохранить')</button>
        <button type="button" class="btn btn-cancel" data-action="close">@lang('Отмена')</button>
    </div>
</form>
