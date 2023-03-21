<form method="post" data-title="{{ $title }}" class="settings-form form-avatar">

    <div class="fields-wrap">
        <div class="avatar">
            <x-file-image :file="$avatar"/>
        </div>
        <p>{{ $description }}</p>
        {!! $form->getElement('image')->getHtml() !!}
    </div>

    <div class="form-buttons">
        <button type="button" class="btn btn-primary">Добавить фото профиля</button>
    </div>
</form>
