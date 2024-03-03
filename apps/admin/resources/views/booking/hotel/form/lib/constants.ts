import { SelectOption } from 'gts-components/Bootstrap/lib'

import { mapEntitiesToSelectOptions } from '~resources/views/booking/shared/lib/constants'

export const status = [
  { id: 1, name: 'Активный' },
  { id: 2, name: 'Заблокирован' },
  { id: 3, name: 'Архив' },
]

export const statusOptions: SelectOption[] = mapEntitiesToSelectOptions(status)
