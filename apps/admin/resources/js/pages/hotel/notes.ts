import '../main'
import HtmlEditor from "../../plugins/htmleditor/htmleditor"

$(document).ready((): void => {
  (new HtmlEditor('#hotel-notes-textarea', {}))
    .init();
});
