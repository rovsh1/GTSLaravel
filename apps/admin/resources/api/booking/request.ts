import { computed } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BaseResponse, DateResponse, useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'
import { FileResponse } from '~api/hotel/images'

import { getNullableRef } from '~lib/vue'

export interface BookingRequestPayload {
  bookingID: BookingID
}

export interface BookingRequest {
  id: number
  type: number
  dateCreate: DateResponse
}

export interface DownloadDocumentRequest {
  bookingID: BookingID
  requestID: number
}

export const useBookingRequestListAPI = (props: MaybeRef<BookingRequestPayload>) =>
  useAdminAPI(props, ({ bookingID }) => `/hotel-booking/${bookingID}/request/list`)
    .get()
    .json<BookingRequest[]>()

export const sendBookingRequest = (props: MaybeRef<BookingRequestPayload>) =>
  useAdminAPI(
    props,
    ({ bookingID }) => `/hotel-booking/${bookingID}/request`,
    { immediate: true },
  )
    .post(computed<string>(() => JSON.stringify(
      getNullableRef<BookingRequestPayload, any>(
        props,
        (): any => null,
      ),
    )), 'application/json')
    .json<BaseResponse>()

const getDocumentFileInfo = (props: MaybeRef<DownloadDocumentRequest>) =>
  useAdminAPI(
    props,
    ({ bookingID, requestID }) => `/hotel-booking/${bookingID}/request/${requestID}/file`,
    { immediate: true },
  )
    .get()
    .json<FileResponse>()

export const downloadRequestDocument = async (props: MaybeRef<DownloadDocumentRequest>): Promise<void> => {
  const { data: fileInfo } = await getDocumentFileInfo(props)
  if (!fileInfo.value) {
    return
  }
  window.open(fileInfo.value.url, '_blank')
}
