<nav class="paginator px-4 pb-3 d-flex">
    <ul class="pagination mb-0">
        @if ($pages->previous)
            <li class="page-item prev">
                    <?= $paginator->link($pages->previous, '<i class="icon">navigate_before</i>', 'page-link') ?>
            </li>
        @endif
        @if ($pages->firstPageInRange > 2)
            <li class="page-item"><?= $paginator->link(1, null, 'page-link') ?></li>
            <li class="page-item">
                <span class="page-link">...</span>
            </li>
        @endif
        @foreach ($pages->pagesInRange as $page)
            <li class="page-item <?=($page == $pages->current ? ' active' : '')?>">
                    <?= $paginator->link($page, null, 'page-link') ?>
            </li>
        @endforeach
        @if ($pages->lastPageInRange < $pages->last - 1)
            <li class="page-item">
                <span class="page-link">...</span>
            </li>
            <li class="page-item"><?= $paginator->link($pages->last, null, 'page-link') ?></li>
        @endif
        @if ($pages->next)
            <li class="page-item next" title=""><?= $paginator->link($pages->next, '<i class="icon">navigate_next</i>', 'page-link') ?></li>
        @endif
    </ul>
    <div class="ms-2">Всего: <b><?= $paginator->getCount() ?></b></div>
</nav>
