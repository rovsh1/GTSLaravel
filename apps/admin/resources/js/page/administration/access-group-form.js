import "../main";
import PermissionsControl from "../../app/components/permissions-control/control";

$(document).ready(function () {
    const prototypes = get_meta_content('prototypes', true);
    const rules = get_meta_content('rules', true);
    const control = new PermissionsControl(prototypes, rules);

    const $tabs = $('#permissions-tabs button');
    const $permissions = $('#permissions').append(control.el);

    $tabs.click(function () {
        const category = $(this).data('category');

        $tabs.filter('.active').removeClass('active');
        $(this).addClass('active');

        control.filter(p => p.category === category);
    });

    control.filter(p => p.category === 'reservation');
});
