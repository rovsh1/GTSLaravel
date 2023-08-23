import { Select2Option } from '~resources/views/hotel-booking/form/lib/types'

export const mapRoomsNameToSelect2Options = (roomsName: string[]): Select2Option[] => roomsName.map(
  (roomName) => ({ id: roomName, text: roomName }),
)
