import '~resources/views/main'
import HtmlEditor from '~resources/js/plugins/htmleditor/htmleditor'

$(() => {
  (new HtmlEditor('#hotel-notes-textarea', {}))
    .init()
})
