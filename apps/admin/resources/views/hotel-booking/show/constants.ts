import { ExternalNumberTypeEnum } from '~api/booking/details'
import { MarkupCondition } from '~api/hotel/markup-settings'

import { SelectOption } from '~components/Bootstrap/lib'

export interface EntityInterface {
  id: number
  name: string
}

const mapEntitiesToSelectOptions = (entities: EntityInterface[]): SelectOption[] => entities.map(
  (entity) => ({ value: entity.id, label: entity.name }),
)

export const genders = [
  { id: 1, name: 'Мужской' },
  { id: 2, name: 'Женский' },
]

export const residentTypes = [
  { id: 1, name: 'Резидент' },
  { id: 0, name: 'Не резидент' },
]

export const roomStatuses = [
  { id: 1, name: 'Ожидает подтверждения' },
  { id: 2, name: 'Отменен' },
  { id: 3, name: 'Подтвержден' },
]

export const externalNumberTypes = [
  { id: ExternalNumberTypeEnum.HotelBookingNumber, name: 'Номер подтверждения брони отеля' },
  { id: ExternalNumberTypeEnum.FullName, name: 'Заезд по ФИО' },
  { id: ExternalNumberTypeEnum.GotoStansBookingNumber, name: 'Номер брони GotoStans' },
]

export const cancelPeriods = [
  { id: 1, name: 'За первую ночь' },
  { id: 2, name: 'За весь период' },
]

export const genderOptions: SelectOption[] = mapEntitiesToSelectOptions(genders)

export const residentTypeOptions: SelectOption[] = mapEntitiesToSelectOptions(residentTypes)

export const roomStatusOptions: SelectOption[] = mapEntitiesToSelectOptions(roomStatuses)

export const externalNumberTypeOptions: SelectOption[] = mapEntitiesToSelectOptions(externalNumberTypes)

export const cancelPeriodOptions: SelectOption[] = mapEntitiesToSelectOptions(cancelPeriods)

export const getConditionLabel = (condition: MarkupCondition) => `с ${condition.from} по ${condition.to} (+${condition.percent}%)`

export const getGenderName = (id: number): string | undefined =>
  genders?.find((gender: any) => gender.id === id)?.name

export const getRoomStatusName = (id: number): string | undefined =>
  roomStatuses?.find((gender: any) => gender.id === id)?.name

export const getCancelPeriodTypeName = (id: number): string | undefined =>
  cancelPeriods.find((cancelPeriod: any) => cancelPeriod.id === id)?.name
