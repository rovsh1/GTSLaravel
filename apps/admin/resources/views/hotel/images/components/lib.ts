import { UseHotelImages } from '~resources/api/hotel/images/list'

import { HotelImageID } from '~api/hotel/images'

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
}

export const isImageAttachedToRoom = (id: HotelImageID, roomImages: UseHotelImages): boolean => roomImages?.find((image) => image?.id === id) !== undefined
