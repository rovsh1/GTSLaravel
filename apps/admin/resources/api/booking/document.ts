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

const downloadFile = async (url: string, filename: string): Promise<void> => {
  window.open(url)
  // @todo проверить почему скачивается пустой файл
  return
  const response = await fetch(url, { mode: 'no-cors' })
  const blob = await response.blob()
  const href = URL.createObjectURL(blob)
  const anchorElement = document.createElement('a')

  anchorElement.href = href
  anchorElement.download = filename
  document.body.appendChild(anchorElement)
  anchorElement.click()
  document.body.removeChild(anchorElement)
  URL.revokeObjectURL(href)
}

export const downloadDocument = async (props: MaybeRef<DownloadDocumentRequest>): Promise<void> => {
  const { data: fileInfo } = await getDocumentFileInfo(props)
  if (!fileInfo.value) {
    return
  }
  await downloadFile(fileInfo.value.url, fileInfo.value.name)
}
