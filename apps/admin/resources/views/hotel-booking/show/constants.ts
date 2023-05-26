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

export const genderOptions: SelectOption[] = mapEntitiesToSelectOptions(genders)

export const residentTypeOptions: SelectOption[] = mapEntitiesToSelectOptions(residentTypes)

export const roomStatusOptions: SelectOption[] = mapEntitiesToSelectOptions(roomStatuses)

export const getGenderName = (id: number): string | undefined =>
  genders?.find((gender: any) => gender.id === id)?.name

export const getRoomStatusName = (id: number): string | undefined =>
  roomStatuses?.find((gender: any) => gender.id === id)?.name
