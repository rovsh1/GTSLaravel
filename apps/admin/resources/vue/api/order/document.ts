import { MaybeRef } from '@vueuse/core'
import { downloadFile } from 'gts-common/helpers/download-file'

import { useAdminAPI } from '~api'
import { FileResponse } from '~api/hotel/images'

export interface DownloadDocumentRequest {
  orderID: number
  documentType: 'voucher' | 'invoice'
  documentID: number
}

const getDocumentFileInfo = (props: MaybeRef<DownloadDocumentRequest>) =>
  useAdminAPI(
    props,
    ({ orderID, documentID, documentType }) => `/booking-order/${orderID}/${documentType}/${documentID}/file`,
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
