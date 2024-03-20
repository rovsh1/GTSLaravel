import HtmlEditor from '~resources/js/plugins/htmleditor/htmleditor'

import '~resources/views/main'

$(async () => {
  const langs = ['ru', 'uz', 'en']

  const initHtmlEditor = async (lang: string) => (new HtmlEditor(`#hotel-notes-textarea-${lang}`, {})).init()
  /* eslint-disable no-restricted-syntax, no-await-in-loop */
  for (const lang of langs) {
    await initHtmlEditor(lang)
  }
})
