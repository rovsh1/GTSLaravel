import { showDataModal } from 'gts-common/show-data'

import '~resources/views/main'

$(() => {
  showDataModal([{
    controlSelector: '.payload .btn-data-content',
    attributeName: 'data-content',
    modalTitle: 'Опиание',
  }, {
    controlSelector: '.context .btn-data-content',
    attributeName: 'data-content',
    modalTitle: 'Информация',
  }])
})
