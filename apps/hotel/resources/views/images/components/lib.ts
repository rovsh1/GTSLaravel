import { HotelImageID } from '~api/hotel/images'
import { UseHotelImages } from '~api/hotel/images/list'

export type SelectedFile = {
  id: string
  name: string
  size: string
  type: string
  src: string
  raw: File
}

export type AttachmentDialogImageProp = {
  id: HotelImageID
  src: string
  alt: string
  isMain: boolean
}

export type UploadStatus = {
  name: string
  status: boolean
}

export const isImageAttachedToRoom = (id: HotelImageID, roomImages: UseHotelImages): boolean => roomImages?.find((image) => image?.id === id) !== undefined
