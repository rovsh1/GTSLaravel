function sitemapBtnHandler(e) {
	const $sitemap = $('div.sitemap');
	if (!$sitemap.is(e.target) && $sitemap.find(e.target).length === 0) {
		$('#btn-sitemap').click();
	}
}

export default function bootSitemap() {
	$('#btn-sitemap').click((e) => {
		e.stopPropagation();

		if (document.body.classList.contains('sitemap-expanded')) {
			document.body.classList.remove('sitemap-expanded');
			document.body.removeEventListener('click', sitemapBtnHandler);
		} else {
			document.body.classList.add('sitemap-expanded');
			document.body.addEventListener('click', sitemapBtnHandler);
		}
	});

	$('#sitemap-categories a').click(function (e) {
		e.preventDefault();

		const item = $(this).parent();
		const category = item.data('category');

		$('#sitemap-categories .current').removeClass('current');
		item.addClass('current');

		const menus = $('#sitemap-categories-menus > div');
		menus.each((i, m) => {
			if ($(m).data('category') === category) {
				$(m).show();
			} else {
				$(m).hide();
			}
		});
	});

	if ($('#sitemap-categories .current').length === 0) {
		$('#sitemap-categories div:first-child a').click();
	}

	$('#btn-sidebar-toggle').click(e => {
		$('#sidebar').toggleClass('submenu-collapsed');

		$(this).find('i');
	});
}