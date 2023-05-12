import { HotelImageID } from '~api/hotel/images'
import { HotelRoom } from '~api/hotel/room'

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

export const isImageAttachedToRoom = (id: HotelImageID, room: HotelRoom): boolean =>
  room.images.find((image) => image.id === id) !== undefined
