import { AirportInfo, CarBid, RailwayStationInfo, ServiceInfo } from '~api/booking/service'

type BaseBookingDetails = {
  id: number
  serviceInfo: ServiceInfo
}

export type BookingTransferToAirportDetails = BaseBookingDetails & {
  city: CityInfo
  airportInfo: AirportInfo
  meetingTablet?: string
  flightNumber?: string
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingTransferFromAirportDetails = BaseBookingDetails & {
  city: CityInfo
  airportInfo: AirportInfo
  meetingTablet?: string
  flightNumber?: string
  arrivalDate?: string
  carBids?: CarBid[]
}

export type BookingTransferFromRailwayDetails = BaseBookingDetails & {
  city: CityInfo
  railwayInfo: RailwayStationInfo
  meetingTablet?: string
  trainNumber?: string
  arrivalDate?: string
  carBids?: CarBid[]
}

export type BookingTransferToRailwayDetails = BaseBookingDetails & {
  city: CityInfo
  railwayInfo: RailwayStationInfo
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

export type CityInfo = {
  id: number
  name: string
}

export type BookingCarRentWithDriverDetails = BaseBookingDetails & {
  city: CityInfo
  bookingPeriod?: BookingPeriod
  meetingTablet?: string
  meetingAddress?: string
  carBids?: CarBid[]
}

export type BookingIntercityTransferDetails = BaseBookingDetails & {
  fromCity: CityInfo
  toCity: CityInfo
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingDayCarTripDetails = BaseBookingDetails & {
  city: CityInfo
  destinationsDescription?: string
  departureDate?: string
  carBids?: CarBid[]
}

export type BookingCipInAirportDetails = BaseBookingDetails & {
  city: CityInfo
  airportInfo: AirportInfo
  flightNumber?: string
  serviceDate?: string
  guestIds?: number[]
}
