import "../../bootstrap"

require("gsv-pkg/support/http");
require("gsv-pkg/ui/window");

import DateRange from "gsv-pkg/support/daterange"
//import Timeout from "@core/timeout"

Object.assign(window, {
	DateRange: DateRange,
	Timeout: Timeout
});

//import "../../vendor/jquery-ui"
//import FileManager from "../../plugins/filemanager/manager"

$(document).ready(function () {
	const fileManager = new FileManager(document.body, {});
	const url_string = window.location.href;
	const url = new URL(url_string);
	const type = url.searchParams.get("type");
	if (type)
		fileManager.filterByType(type);
	fileManager.setView(url.searchParams.get("view") || 'grid');

	if (window.parent) {
		fileManager.bind('choose', function (file) {
			window.parent.postMessage({
				sender: 'filemanager',
				mimeType: file.mimeType,
				name: file.name,
				url: file.src
			}, "*");
		});
	}
});

//import("./page/auth.js");
