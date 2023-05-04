import { DateTime, Interval, Settings } from 'luxon'

import { APIDate } from '~resources/lib/api'

Settings.defaultLocale = 'ru'

export const formatDateToAPIDate = (date: Date): APIDate => DateTime
  .fromJSDate(date).toFormat('yyyy-LL-dd')

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
