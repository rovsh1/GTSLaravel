import axios from '~resources/js/app/api'
import { getHumanRequestType } from '~resources/views/booking/shared/lib/constants'

import { downloadDocument } from '~api/booking/document'
import { BookingRequest } from '~api/booking/request'

import { formatDateTime } from '~lib/date'
import createPopover, { PopoverItem } from '~lib/popover/popover'

import '~resources/views/main'

$(() => {
  $('.btn-request-download').on('click', async (e: any) => {
    e.preventDefault()
    const bookingId = $(e.currentTarget).parent().parent().parent()
      .data('id')
    const { data: requests } = await axios.get<BookingRequest[]>(`/hotel-booking/${bookingId}/request/list`)
    const popoverContentDownload: Array<PopoverItem> = []
    const groupedRequests: { [type: string]: BookingRequest[] } = requests.reduce((result, request) => {
      const resultItem = result
      if (!resultItem[request.type]) {
        resultItem[request.type] = []
      }
      resultItem[request.type].push(request)
      return resultItem
    }, {} as any)
    Object.keys(groupedRequests).forEach((groupName) => {
      groupedRequests[groupName].sort(
        (a: BookingRequest, b: BookingRequest) =>
          new Date(b.dateCreate).getTime() - new Date(a.dateCreate).getTime(),
      )
      const groupFirstRequest = groupedRequests[groupName].length ? groupedRequests[groupName][0] : null
      if (groupFirstRequest) {
        const popoverContentActions: PopoverItem = {
          text: `Запрос на ${getHumanRequestType(groupFirstRequest.type)} от ${formatDateTime(
            groupFirstRequest.dateCreate,
          )}`,
          buttonText: 'Скачать',
          callback: async () => {
            if (groupFirstRequest?.id) {
              await downloadDocument({
                documentID: groupFirstRequest.id,
                documentType: 'request',
                bookingID: bookingId,
              })
            }
          },
        }
        popoverContentDownload.push(popoverContentActions)
      }
    })
    createPopover({
      relationElement: e.currentTarget,
      textForEmpty: 'Нет файлов для загрузки',
      content: popoverContentDownload,
    })
  })
})
