import { Percent } from '~api/hotel/markup-settings'

export type Price = number

export interface CalculateRoomPricePayload {
  dayPrice: Price
  nightsCount?: number
  discountPercent?: Percent | null
  roomsCount?: number
  earlyCheckInPercent?: Percent | null
  lateCheckOutPercent?: Percent | null
}

const calculateMarkup = (dayPrice: Price, percent: Percent) => dayPrice * (percent / 100)

export const getRoomCalculatedPrice = (payload: CalculateRoomPricePayload): number => {
  let nightsCount = 0
  if (payload.nightsCount) {
    nightsCount = payload.nightsCount
  }
  let roomsCount = 1
  if (payload.roomsCount) {
    roomsCount = payload.roomsCount
  }
  let discount = 0
  if (payload.discountPercent) {
    discount = calculateMarkup(payload.dayPrice, payload.discountPercent)
  }
  let earlyCheckIn = 0
  if (payload.earlyCheckInPercent) {
    earlyCheckIn = calculateMarkup(payload.dayPrice, payload.earlyCheckInPercent)
  }
  let lateCheckOut = 0
  if (payload.lateCheckOutPercent) {
    lateCheckOut = calculateMarkup(payload.dayPrice, payload.lateCheckOutPercent)
  }

  return (payload.dayPrice - discount) * roomsCount * nightsCount + (earlyCheckIn + lateCheckOut) * roomsCount
}
