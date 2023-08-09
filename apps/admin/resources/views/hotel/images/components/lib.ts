import { HotelImage, HotelImageID } from '~api/hotel/images'
import { HotelRoom } from '~api/hotel/room'
import { UseHotelImages, UseHotelRoomImages } from '~resources/api/hotel/images/list'

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
