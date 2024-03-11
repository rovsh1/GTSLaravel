import { useAdminAPI } from '~api/index'

export interface RoomTypesResponse {
  id: number
  name: string
}

export const useRoomTypesGetAPI = () =>
  useAdminAPI({ }, () => '/hotel-room-type/list')
    .get()
    .json<RoomTypesResponse[]>()
