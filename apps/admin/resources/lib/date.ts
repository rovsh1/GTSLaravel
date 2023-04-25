import { DateTime, Interval, Settings } from 'luxon'

Settings.defaultLocale = 'ru'

export const getEachDayInMonth = (date: Date): DateTime[] => {
  const month = DateTime.fromJSDate(date)
  return Interval
    .fromDateTimes(month.startOf('month'), month.endOf('month'))
    .splitBy({ days: 1 })
    .map((interval) => {
      if (interval.isValid) return interval.start
      throw new Error()
    })
    .filter((day): day is DateTime => day !== null)
}

export const getEachMonthInYear = (date: Date): DateTime[] => {
  const year = DateTime.fromJSDate(date)
  return Interval
    .fromDateTimes(year.startOf('year'), year.endOf('year'))
    .splitBy({ months: 1 })
    .map((interval) => {
      if (interval.isValid) return interval.start
      throw new Error()
    })
    .filter((month): month is DateTime => month !== null)
}
