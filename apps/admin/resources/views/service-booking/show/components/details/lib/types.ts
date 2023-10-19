import { AirportInfo, CarBid, RailwayInfo, ServiceInfo } from '~api/booking/service'

type BaseBookingDetails = {
  id: number
  serviceInfo: ServiceInfo
}

export type BookingTransferToAirportDetails = BaseBookingDetails & {
  airportInfo?: AirportInfo
  meetingTablet?: string
  flightNumber?: string
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingTransferFromAirportDetails = BaseBookingDetails & {
  airportInfo?: AirportInfo
  meetingTablet?: string
  flightNumber?: string
  arrivalDate?: string
  carBids?: CarBid[]
}

export type BookingTransferFromRailwayDetails = BaseBookingDetails & {
  railwayInfo?: RailwayInfo
  meetingTablet?: string
  flightNumber?: string
  arrivalDate?: string
  carBids?: CarBid[]
}

export type BookingTransferToRailwayDetails = BaseBookingDetails & {
  railwayInfo?: RailwayInfo
  meetingTablet?: string
  flightNumber?: string
  arrivalDate?: string
  carBids?: CarBid[]
}

export type BookingOtherDetails = BaseBookingDetails & {
  description?: string
}

export type BookingCarRentWithDriverDetails = BaseBookingDetails & {
  hoursLimit?: number
  date?: string
  carBids?: CarBid[]
}

export type BookingIntercityTransferDetails = BaseBookingDetails & {
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingDayCarTripDetails = BaseBookingDetails & {
  destinationsDescription?: string
  date?: string
  carBids?: CarBid[]
}

export type BookingCipInAirportDetails = BaseBookingDetails & {
  flightNumber?: string
  serviceDate?: string
  guestIds?: number[]
}
