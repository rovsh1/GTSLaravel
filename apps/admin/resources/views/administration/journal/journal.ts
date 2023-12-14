import { showDataModal } from '~lib/show-data'

import '~resources/views/main'

$(() => {
  showDataModal([{
    controlSelector: '.column-payload .btn-data-content',
    attributeName: 'data-content',
    modalTitle: 'Опиание',
  }, {
    controlSelector: '.column-context .btn-data-content',
    attributeName: 'data-content',
    modalTitle: 'Информация',
  }])
})
