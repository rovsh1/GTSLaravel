import HtmlEditor from '~resources/js/plugins/htmleditor/htmleditor'

import '~resources/views/main'

$(async () => {
  (new HtmlEditor('#room-text-textarea', {})).init()
})
