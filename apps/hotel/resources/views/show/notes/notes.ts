import HtmlEditor from '~resources/js/plugins/htmleditor/htmleditor'

import '~resources/views/main'

$(() => {
  (new HtmlEditor('#hotel-notes-textarea', {}))
    .init()
})
