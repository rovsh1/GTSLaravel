<nav class="paginator">
	<ul>
		<?php
		if ($pages->previous) { ?>
		<li class="page prev"><?php echo $paginator->link($pages->previous, '&larr; назад'); ?></li><?php }
		if ($pages->firstPageInRange > 2)
			echo '<li class="page first">', $paginator->link(1), '</li><li class="dot">...</li>';
		foreach ($pages->pagesInRange as $page) { ?>
		<li class="page<?php echo($page == $pages->current ? ' current' : ''); ?>">
			<?php if ($page != $pages->current)
				echo $paginator->link($page);
			else echo $page; ?>
		</li><?php }
		if ($pages->lastPageInRange < $pages->last - 1)
			echo '<li class="dot">...</li><li class="last">', $paginator->link($pages->last), '</li>';
		if ($pages->next) { ?>
		<li class="next" title=""><?php echo $paginator->link($pages->next, 'вперед &rarr;'); ?></li><?php } ?>
		<li class="count">Всего: <?php echo $paginator->getCount(); ?></li>
	</ul>
</nav>
