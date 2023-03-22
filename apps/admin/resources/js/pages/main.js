import '../bootstrap'

import "../vendor/jquery.cookie"
import "../vendor/moment"
import "../vendor/daterangepicker"

import "../libs/dialog/helpers"

import "../plugins/multiselect"
import "../plugins/child-combo"

import "../app/support/functions"
import "../app/helpers"
import "../app/plugins/buttons/delete-button"

/*
import "../vendor/jquery.mask"

require("gsv-pkg/support/http");
require("gsv-pkg/ui/window");

Object.assign($.fn, {});

Object.assign(window, {});
*/

import bootSitemap from "../app/providers/sitemap";
import bootCookies from "../app/providers/cookies";
import bootForms from "../app/providers/forms";
import bootGrid from "../app/providers/grids";

$(document).ready(function () {
	$.ajaxSetup({
		headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')}
	});

	bootSitemap();
	bootCookies();
	bootForms();
	bootGrid();

	document.body.classList.add('loaded');
});
