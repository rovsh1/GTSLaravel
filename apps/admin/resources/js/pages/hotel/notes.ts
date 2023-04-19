import '../main'
import HtmlEditor from "../../plugins/htmleditor/htmleditor"

$(() => {
  (new HtmlEditor('#hotel-notes-textarea', {}))
    .init();
});
