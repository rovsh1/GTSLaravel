import '../bootstrap';

import "../vendor/jquery.cookie";

import "../app/helpers"

require("gsv-pkg/framework/bootstrap");
/*
import "../vendor/jquery.mask"

require("gsv-pkg/support/http");
require("gsv-pkg/ui/window");

Object.assign($.fn, {});

Object.assign(window, {});
*/

import LayoutProvider from "../app/providers/layout";
import CookiesProvider from "../app/providers/cookies";

$(document).ready(function () {
    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')}
    });

    app()
        .register(LayoutProvider)
        .register(CookiesProvider)
        .boot();
});
