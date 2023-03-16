<form method="post" data-title="{{$title}}" data-cls="window-form <?= isset($cls) ? $cls : '' ?>">
	<?= isset($description) ? '<p>' . $description . '</p>' : '' ?>
	<?= $form->report() ?>
	<div class="fields-wrap">
		<?= $form->render() ?>
	</div>

	<div class="form-buttons">
		<button type="button" class="btn btn-cancel" data-action="close">@lang('Отмена')</button>
		<button type="submit" class="btn btn-submit">@lang('Сохранить')</button>
	</div>
</form>
