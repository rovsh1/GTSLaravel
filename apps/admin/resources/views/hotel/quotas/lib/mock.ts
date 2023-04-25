type QuotaID = number
type RoomID = number
type HotelID = number

type Hotel = {
  id: HotelID
}

const hotelMock1: Hotel = {
  id: 1,
}

type Room = {
  id: RoomID
  hotel_id: HotelID
  name: string
  custom_name: string
  rooms_number: number
  guests_number: number
}

const roomMock1: Room = {
  id: 1183,
  hotel_id: hotelMock1.id,
  name: 'Стандартный двухместный',
  custom_name: '183',
  rooms_number: 10,
  guests_number: 2,
}

const roomMock2: Room = {
  id: 1184,
  hotel_id: hotelMock1.id,
  name: 'Стандартный одноместный',
  custom_name: '184',
  rooms_number: 15,
  guests_number: 1,
}

export const roomsMock: Room[] = [roomMock1, roomMock2]

export type QuotaStatus = 0 | 1

export type Quota = {
  id: QuotaID
  // Y-m-d
  date: string
  room_id: RoomID
  // 0 = closed, 1 = opened
  status: QuotaStatus
  release_days: number
  count_available: number
  count_booked: number
  count_reserved: number
}

export const quotasMock: Quota[] = [
  {
    id: 1,
    date: '2023-03-05',
    room_id: roomMock1.id,
    status: 1,
    release_days: 2,
    count_available: 4,
    count_booked: 2,
    count_reserved: 1,
  },
  {
    id: 2,
    date: '2023-04-05',
    room_id: roomMock1.id,
    status: 0,
    release_days: 5,
    count_available: 3,
    count_booked: 8,
    count_reserved: 2,
  },
  {
    id: 3,
    date: '2023-03-05',
    room_id: roomMock2.id,
    status: 0,
    release_days: 1,
    count_available: 6,
    count_booked: 11,
    count_reserved: 4,
  },
  {
    id: 4,
    date: '2023-04-05',
    room_id: roomMock2.id,
    status: 1,
    release_days: 7,
    count_available: 1,
    count_booked: 6,
    count_reserved: 9,
  },
  {
    id: 5,
    date: '2023-04-06',
    room_id: roomMock2.id,
    status: 0,
    release_days: 7,
    count_available: 1,
    count_booked: 6,
    count_reserved: 9,
  },
]
