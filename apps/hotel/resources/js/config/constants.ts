import { SelectOption } from '~components/Bootstrap/lib'

export const daysOfWeek = [
  { value: 1, name: 'понедельник', shortName: 'Пн' },
  { value: 2, name: 'вторник', shortName: 'Вт' },
  { value: 3, name: 'среда', shortName: 'Ср' },
  { value: 4, name: 'четверг', shortName: 'Чт' },
  { value: 5, name: 'пятница', shortName: 'Пт' },
  { value: 6, name: 'суббота', shortName: 'Сб' },
  { value: 7, name: 'воскресенье', shortName: 'Вс' },
]

export interface EntityInterface {
  value: number
  name: string
  shortName: string
}

export enum CancelConditionsValueType {
  PERCENT = 1,
  ABSOLUTE,
}

export const mapEntitiesToSelectOptions = (entities: EntityInterface[]): SelectOption[] => entities.map(
  (entity) => ({ value: entity.value, label: entity.name }),
)

export const daysOfWeekOptions: SelectOption[] = mapEntitiesToSelectOptions(daysOfWeek)
