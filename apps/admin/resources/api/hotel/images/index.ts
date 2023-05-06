export type HotelImageID = number

export type FileResponse = {
  guid: string
  name: string
  url: string
  entity_id: number
}

export type HotelImageResponse = {
  id: HotelImageID
  title: string | null
  order: number
  hotel_id: number
  file: FileResponse
}
