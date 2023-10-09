import axios from '~resources/js/app/api'
import { getHumanRequestType } from '~resources/views/booking/lib/constants'

import { downloadDocument } from '~api/booking/airport/document'
import { BookingRequest } from '~api/booking/airport/request'
import { BookingAvailableActionsResponse } from '~api/booking/airport/status'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { formatDateTime } from '~lib/date'
import createPopover, { PopoverItem } from '~lib/popover/popover'

import '~resources/views/main'

$(() => {
  $('#form_data_hotel_id').childCombo({
    url: '/hotels/search',
    disabledText: 'Выберите город',
    parent: $('#form_data_city_id'),
    dataIndex: 'city_id',
  })

  $('#form_data_hotel_room_id').childCombo({
    urlGetter: (hotelId: number) => `/hotels/${hotelId}/rooms/list`,
    disabledText: 'Выберите отель',
    parent: $('#form_data_hotel_id'),
    dataIndex: 'hotel_id',
  })

  const selectedBookings: string[] = []

  const $deleteBookingsButton = $('<a />', {
    href: '#',
    html: '<i class="icon">delete</i>Удалить брони',
    class: 'btn btn-delete text-danger border-0 disabled',
  }).click(async (event) => {
    event.preventDefault()

    const { result: isConfirmed, toggleLoading } = await showConfirmDialog('Удалить запись?', 'btn-danger')
    if (isConfirmed) {
      toggleLoading()
      await axios.delete('/service-booking/bulk', { data: { ids: selectedBookings } })
      location.reload()
    }
  })

  $('.content-header a.btn-add').after($deleteBookingsButton)

  $('.js-select-booking').change((event: any): void => {
    const $checkbox = $(event.target)
    const bookingId = $checkbox.data('booking-id')
    if ($checkbox.is(':checked')) {
      selectedBookings.push(bookingId)
      $deleteBookingsButton.toggleClass('disabled', false)
      return
    }

    const index = selectedBookings.indexOf(bookingId)
    if (index !== -1) {
      selectedBookings.splice(index, 1)
      if (selectedBookings.length === 0) {
        $deleteBookingsButton.toggleClass('disabled', true)
      }
    }
  })

  $('.btn-request-download').on('click', async (e: any) => {
    e.preventDefault()
    const bookingId = $(e.currentTarget).parent().parent().parent()
      .data('id')
    const { data: requests } = await axios.get<BookingRequest[]>(`/service-booking/${bookingId}/request/list`)
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

  $('.btn-request-send').on('click', async (e: any) => {
    e.preventDefault()
    const bookingId = $(e.currentTarget).parent().parent().parent()
      .data('id')
    const { data: availableActions } = await axios.get<BookingAvailableActionsResponse>(`/service-booking/${bookingId}/actions/available`)
    const popoverContentSend: Array<PopoverItem> = []
    let requestText
    if (availableActions.canSendBookingRequest) {
      requestText = 'Запрос на бронирование еще не отправлен'
    }
    if (availableActions.canSendChangeRequest) {
      requestText = 'Ожидание изменений и отправки запроса'
    }
    if (availableActions.canSendCancellationRequest) {
      requestText = 'Бронирование подтверждено, до выставления счета доступен запрос на отмену'
    }
    if (availableActions.isRequestable && requestText) {
      const popoverContentActions: PopoverItem = {
        text: requestText,
        buttonText: 'Отправить',
        callback: async () => {
          await axios.post(`/service-booking/${bookingId}/request`)
          location.reload()
        },
      }
      popoverContentSend.push(popoverContentActions)
    }
    createPopover({
      relationElement: e.currentTarget,
      textForEmpty: 'Нет возможных действий',
      content: popoverContentSend,
    })
  })
})
