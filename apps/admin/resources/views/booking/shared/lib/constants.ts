import { SelectOption } from 'gts-components/Bootstrap/lib'

import { Client } from '~resources/vue/api/client'

import { ExternalNumberTypeEnum } from '~api/booking/hotel/details'
import { MarkupCondition } from '~api/hotel/markup-settings'

export interface EntityInterface {
  id: number | string
  name: string
  group?: string
}

export type Select2Option = {
  id: string | number | ''
  text: string
}

export const mapEntitiesToSelectOptions = (entities: EntityInterface[]): SelectOption[] => entities.map(
  (entity) => ({ value: entity.id, label: entity.name, group: entity.group }),
)

export const genders = [
  { id: 1, name: 'Мужской' },
  { id: 2, name: 'Женский' },
]

export const residentTypes = [
  { id: 1, name: 'Резидент' },
  { id: 2, name: 'Не резидент' },
  { id: 3, name: 'Все' },
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

const clientTypes = [
  { id: 1, name: 'Физическое лицо' },
  { id: 2, name: 'Юридическое лицо' },
]

const humanRequestType = [
  { id: 1, name: 'бронирование' },
  { id: 3, name: 'отмену' },
]

const languages = [
  { id: 'ru', name: 'Русский' },
  { id: 'en', name: 'Английский' },
]

export const genderOptions: SelectOption[] = mapEntitiesToSelectOptions(genders)

export const residentTypeOptions: SelectOption[] = mapEntitiesToSelectOptions(residentTypes)

export const externalNumberTypeOptions: SelectOption[] = mapEntitiesToSelectOptions(externalNumberTypes)

export const cancelPeriodOptions: SelectOption[] = mapEntitiesToSelectOptions(cancelPeriods)

export const clientTypeOptions: SelectOption[] = mapEntitiesToSelectOptions(clientTypes)

export const languageOptions: SelectOption[] = mapEntitiesToSelectOptions(languages)

export const getConditionLabel = (condition: MarkupCondition) => `с ${condition.from} по ${condition.to} (+${condition.percent}%)`

export const getGenderName = (id: number): string | undefined =>
  genders?.find((gender: any) => gender.id === id)?.name

export const getCancelPeriodTypeName = (id: number): string | undefined =>
  cancelPeriods.find((cancelPeriod: any) => cancelPeriod.id === id)?.name

export const getHumanRequestType = (typeId: number): string | undefined => {
  const preparedType = humanRequestType?.find((type: any) => type.id === typeId)?.name
  return preparedType || 'изменение'
}

export const getDaysWord = (number: number) => {
  const lastTwoDigits = number % 100
  if (lastTwoDigits >= 11 && lastTwoDigits <= 19) {
    return 'дней'
  }
  const lastDigit = number % 10
  if (lastDigit === 1) {
    return 'день'
  }
  if (lastDigit >= 2 && lastDigit <= 4) {
    return 'дня'
  }
  return 'дней'
}

export const mapClientsToSelect2Options = (clients: Client[]): Select2Option[] => clients.map(
  (client) => ({ id: client.id, text: client.name }),
)
