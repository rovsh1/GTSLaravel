import { AirportInfo, CarBid, RailwayStationInfo, ServiceInfo } from '~api/booking/service'

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
  railwayInfo?: RailwayStationInfo
  meetingTablet?: string
  trainNumber?: string
  arrivalDate?: string
  carBids?: CarBid[]
}

export type BookingTransferToRailwayDetails = BaseBookingDetails & {
  railwayInfo?: RailwayStationInfo
  meetingTablet?: string
  trainNumber?: string
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingOtherDetails = BaseBookingDetails & {
  description?: string
}

export type BookingPeriod = {
  dateFrom: string
  dateTo: string
}

export type BookingCarRentWithDriverDetails = BaseBookingDetails & {
  bookingPeriod?: BookingPeriod
  meetingTablet?: string
  meetingAddress?: string
  carBids?: CarBid[]
}

export type BookingIntercityTransferDetails = BaseBookingDetails & {
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingDayCarTripDetails = BaseBookingDetails & {
  destinationsDescription?: string
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingCipInAirportDetails = BaseBookingDetails & {
  flightNumber?: string
  serviceDate?: string
  airportInfo?: AirportInfo
  guestIds?: number[]
}
