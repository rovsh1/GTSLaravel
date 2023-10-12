import { DateTime, Interval, Settings } from 'luxon'

import { APIDate, DateResponse } from '~api'

Settings.defaultLocale = 'ru'

export const parseAPIDate = (date: DateResponse): DateTime => DateTime.fromISO(date)

export const formatDateToAPIDate = (date: Date): APIDate => DateTime
  .fromJSDate(date).toFormat('yyyy-LL-dd')

export const formatDateTimeToAPIDate = (date: DateTime): APIDate => date.toFormat('yyyy-LL-dd')

export const formatDate = (date: DateResponse) => parseAPIDate(date).toLocaleString()

export const formatDateTime = (date: DateResponse) => parseAPIDate(date).toLocaleString(DateTime.DATETIME_SHORT)

export const compareJSDate = (firstDate: Date, secondDate: Date): boolean => {
  if (firstDate.getTime() === secondDate.getTime()) {
    return true
  }
  return false
}

export interface PeriodInterface {
  date_start: DateResponse
  date_end: DateResponse
}

export const formatPeriod = (period: PeriodInterface) => `${formatDate(period.date_start)} - ${formatDate(period.date_end)}`

export const formatSeasonPeriod = (period: PeriodInterface) => {
  const dateStart = parseAPIDate(period.date_start)
  const dateEnd = parseAPIDate(period.date_end)
  return `${dateStart.day} ${dateStart.monthLong} - ${dateEnd.day} ${dateEnd.monthLong} ${dateEnd.year}`
}

export const getMonthName = (date: DateTime) => date.monthLong

export const dateRangeDelimiter = ' - '

export const getEachDayInMonth = (date: Date): Date[] => {
  const month = DateTime.fromJSDate(date)
  return Interval
    .fromDateTimes(month.startOf('month'), month.endOf('month'))
    .splitBy({ days: 1 })
    .map((interval) => {
      if (interval.isValid) return interval.start
      throw new Error('Interval is invalid')
    })
    .filter((dateTime): dateTime is DateTime => dateTime !== null)
    .map((dateTime) => dateTime.toJSDate())
}

export const getEachMonthInYear = (date: Date): DateTime[] => {
  const year = DateTime.fromJSDate(date)
  return Interval
    .fromDateTimes(year.startOf('year'), year.endOf('year'))
    .splitBy({ months: 1 })
    .map((interval) => {
      if (interval.isValid) return interval.start
      throw new Error('Interval is invalid')
    })
    .filter((month): month is DateTime => month !== null)
}

export const isBusinessDay = (date: Date): boolean => {
  const weekDayNumber = Number(DateTime.fromJSDate(date).toFormat('E'))
  if (Number.isNaN(weekDayNumber)) {
    throw new Error('Cannot parse week day number')
  }
  switch (weekDayNumber) {
    case 6:
    case 7:
      return false
    default:
      return true
  }
}
