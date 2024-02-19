import { MaybeRef } from '@vueuse/core'
import { downloadFile } from 'gts-common/download-file'

import { useAdminAPI } from '~api'
import { BookingID } from '~api/booking/models'
import { FileResponse } from '~api/hotel/images'

export interface DownloadDocumentRequest {
  bookingID: BookingID
  documentType: 'request' | 'voucher' | 'invoice'
  documentID: number
}

const getDocumentFileInfo = (props: MaybeRef<DownloadDocumentRequest>) =>
  useAdminAPI(
    props,
    ({ bookingID, documentID, documentType }) => `/booking/${bookingID}/${documentType}/${documentID}/file`,
    { immediate: true },
  )
    .get()
    .json<FileResponse>()

export const downloadDocument = async (props: MaybeRef<DownloadDocumentRequest>): Promise<void> => {
  const { data: fileInfo } = await getDocumentFileInfo(props)
  if (!fileInfo.value) {
    return
  }
  await downloadFile(fileInfo.value.url, fileInfo.value.name)
}
