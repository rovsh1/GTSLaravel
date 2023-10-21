export type QuotasStatusUpdateFormData = {
  period: [Date, Date] | undefined
  daysWeekSelected: string[]
  selectedRoomsID: string[]
  action: Action | null
}

export type QuotasStatusUpdatePayload = {
  dateFrom: Date
  dateTo: Date
  daysWeekSelected: number[]
  selectedRoomsID: number[]
  action: Action
}

export type Action = 'OPEN' | 'CLOSE'

export type ActionsOption = {
  value: Action
  label: string
}
