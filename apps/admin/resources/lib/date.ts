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
