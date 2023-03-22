export function isImage(mime) {
	return /^image\/.*/.test(mime);
}

export function MimeToExtension(mime) {
	if (isImage(mime))
		return 'image';

	const assoc = {
		'application/bmp': 'image',
		'application/x-bmp': 'image',
		'application/x-win-bitmap': 'image',
		'text/css': 'css',
		'text/x-comma-separated-values': 'csv',
		'text/comma-separated-values': 'csv',
		'application/vnd.msexcel': 'csv',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'doc',
		'application/x-msdownload': 'exe',
		'application/x-gtar': 'tar',
		'application/x-gzip': 'zip',
		'text/html': 'html',
		//'application/x-javascript': 'js',
		//'application/json': 'json',
		//'text/json': 'json',
		'text/x-log': 'log',
		'application/pdf': 'pdf',
		'application/octet-stream': 'pdf',
		'application/msword': 'doc',
		'application/x-rar': 'rar',
		'application/rar': 'rar',
		'application/x-rar-compressed': 'rar',
		'text/rtf': 'doc',
		'text/srt': 'txt',
		'application/x-tar': 'tar',
		'application/x-gzip-compressed': 'zip',
		'text/plain': 'txt',
		'text/x-vcard': 'txt',
		'application/xhtml+xml': 'html',
		'application/excel': 'xls',
		'application/msexcel': 'xls',
		'application/x-msexcel': 'xls',
		'application/x-ms-excel': 'xls',
		'application/x-excel': 'xls',
		'application/x-dos_ms_excel': 'xls',
		'application/xls': 'xls',
		'application/x-xls': 'xls',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'xls',
		'application/vnd.ms-excel': 'xls',
		'application/xml': 'xml',
		'text/xml': 'xml',
		'application/x-compress': 'zip',
		'application/x-zip': 'zip',
		'application/zip': 'zip',
		'application/x-zip-compressed': 'zip',
		'application/s-compressed': 'zip',
		'multipart/x-zip': 'zip'
	};

	return assoc[mime] || 'document';
}

export function getMimeImage(mime) {
	const ext = MimeToExtension(mime);
	return '/images/ui/mime/' + ext + '.png';
}
