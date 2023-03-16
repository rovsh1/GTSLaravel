import '../bootstrap';

import "../vendor/jquery.cookie";

import "../app/support/functions";
import "../app/helpers";

import "../plugins/ui/dialog/helpers"

/*
import "../vendor/jquery.mask"

require("gsv-pkg/support/http");
require("gsv-pkg/ui/window");

Object.assign($.fn, {});

Object.assign(window, {});
*/

import bootLayout from "../app/providers/layout";
import bootCookies from "../app/providers/cookies";

$(document).ready(function () {
	$.ajaxSetup({
		headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')}
	});

	bootLayout();
	bootCookies();
});
