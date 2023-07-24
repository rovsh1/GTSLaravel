import { MaybeRef } from '@vueuse/core'

import { useAdminAPI } from '~api'
import { BookingID } from '~api/booking/index'
import { FileResponse } from '~api/hotel/images'

export interface DownloadDocumentRequest {
  bookingID: BookingID
  documentType: 'request' | 'voucher' | 'invoice'
  documentID: number
}

const getDocumentFileInfo = (props: MaybeRef<DownloadDocumentRequest>) =>
  useAdminAPI(
    props,
    ({ bookingID, documentID, documentType }) => `/hotel-booking/${bookingID}/${documentType}/${documentID}/file`,
    { immediate: true },
  )
    .get()
    .json<FileResponse>()

export const downloadDocument = async (props: MaybeRef<DownloadDocumentRequest>): Promise<void> => {
  const { data: fileInfo } = await getDocumentFileInfo(props)
  if (!fileInfo.value) {
    return
  }
  window.open(fileInfo.value.url, '_blank')
}
