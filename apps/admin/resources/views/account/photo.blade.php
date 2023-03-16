<form method="post" data-title="{{$title}}" data-cls="window-form window-avatar">
	@csrf
	<?= $form->report() ?>
	<div class="fields-wrap">
		<div class="avatar"><?=image($avatar)?></div>
		<p><?=$description?></p>
		<?= $form->getElement('image')->render() ?>
	</div>

	<div class="form-buttons">
		<button type="button" class="btn btn-submit">@lang('Добавить фото профиля')</button>
	</div>
</form>
