import { mapEntitiesToSelectOptions } from '~resources/views/hotel-booking/show/lib/constants'

import { SelectOption } from '~components/Bootstrap/lib'

export const status = [
  { id: 1, name: 'Активный' },
  { id: 2, name: 'Заблокирован' },
  { id: 3, name: 'Архив' },
]

export const statusOptions: SelectOption[] = mapEntitiesToSelectOptions(status)
