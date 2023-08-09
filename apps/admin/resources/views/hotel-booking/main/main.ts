import axios from '~resources/js/app/api'

import { BookingRequest } from '~api/booking/request'
import { BookingAvailableActionsResponse } from '~api/booking/status'
import { BookingVoucher } from '~api/booking/voucher'
import { showConfirmDialog } from '~lib/confirm-dialog'
import createPopover, { PopoverItem } from '~lib/popover/popover'
import { getHumanRequestType } from '~lib/human-request-type'
import { formatDateTime } from '~lib/date'
import { downloadDocument } from '~api/booking/document'


import '~resources/views/main'

$(() => {
  // $('#form_data_city_id').elementCity({ countrySelector: '#form_data_country_id' })

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
      await axios.delete('/hotel-booking/bulk', { data: { ids: selectedBookings } })
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

  $('td.column-actions').each(function () {
    const bookingId = $(this).parent().data('id')

    const getNewIconButton = (icon: string) => $('<a />', { href: '#', html: `<i class="icon">${icon}</i>` })

    const $sendButton = getNewIconButton('mail')
      .on('click', async (e: any) => {
        e.preventDefault()
        const { data: availableActions } = await axios.get<BookingAvailableActionsResponse>(`/hotel-booking/${bookingId}/actions/available`)
        let popoverContentSend: Array<PopoverItem> = []
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
          let popoverContentActions: PopoverItem = {
            text: requestText,
            buttonText: "Отправить",
            callback: async () => {
              await axios.post(`/${bookingId}/request`)
              location.reload()
            }
          }
          popoverContentSend.push(popoverContentActions);
        }
        if (availableActions.canSendVoucher) {
          const voucherText = 'При необходимости клиенту можно отправить ваучер'
          let popoverContentVoucher: PopoverItem = {
            text: voucherText,
            buttonText: "Отправить",
            callback: async () => {
              await axios.post(`/${bookingId}/voucher`)
              location.reload()
            }
          }
          popoverContentSend.push(popoverContentVoucher);
        }
        const popover = createPopover({
          relationElement: e.currentTarget,
          textForEmpty: "Нет возможных действий",
          content: popoverContentSend
        })
      })

    const $downloadButton = getNewIconButton('download')
      .on('click', async (e: any) => {
        e.preventDefault()
        const [{ data: requests }, { data: vouchers }] = await Promise.all([
          axios.get<BookingRequest[]>(`/hotel-booking/${bookingId}/request/list`),
          axios.get<BookingVoucher[]>(`/hotel-booking/${bookingId}/voucher/list`),
        ])
        let popoverContentDownload: Array<PopoverItem> = []
        const groupedRequests: { [type: string]: BookingRequest[] } = requests.reduce((result, request) => {
          if (!result[request.type]) {
            result[request.type] = [];
          }
          result[request.type].push(request);
          return result;
        }, {} as any);
        for (const groupName in groupedRequests) {
          groupedRequests[groupName].sort((a: BookingRequest, b: BookingRequest) => new Date(b.dateCreate).getTime() - new Date(a.dateCreate).getTime());
          let groupFirstRequest = groupedRequests[groupName].length ? groupedRequests[groupName][0] : null;
          if(groupFirstRequest) {
            let popoverContentActions: PopoverItem = {
              text: `Запрос на ${getHumanRequestType(groupFirstRequest.type)} от ${formatDateTime(groupFirstRequest.dateCreate)}`,
              buttonText: "Скачать",
              callback: async () => {
                if (groupFirstRequest?.id) {
                  await downloadDocument({ documentID: groupFirstRequest.id, documentType: 'request', bookingID: bookingId })
                }
              }
            }
            popoverContentDownload.push(popoverContentActions);
          }
        }
        vouchers.forEach((voucher) => {
          let popoverContentVouchers: PopoverItem = {
            text: `Ваучер от ${formatDateTime(voucher.dateCreate)}`,
            buttonText: "Скачать",
            callback: async () => {
              await downloadDocument({ documentID: voucher.id, documentType: 'voucher', bookingID: bookingId })
            }
          }
          popoverContentDownload.push(popoverContentVouchers);
        })
        const popover = createPopover({
          relationElement: e.currentTarget,
          textForEmpty: "Нет возможных действий",
          content: popoverContentDownload
        })
      })
    const $buttonsWrapper = $('<div />', { class: 'd-flex flex-row gap-2' }).append($sendButton).append($downloadButton)
    $(this).append($buttonsWrapper)
  })
})
