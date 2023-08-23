import { Client } from '~resources/api/client'
import { mapEntitiesToSelectOptions } from '~resources/views/hotel-booking/show/lib/constants'

import { SelectOption } from '~components/Bootstrap/lib'

import { Select2Option } from './types'

export const status = [
  { id: 1, name: 'Активный' },
  { id: 2, name: 'Заблокирован' },
  { id: 3, name: 'Архив' },
]

export const statusOptions: SelectOption[] = mapEntitiesToSelectOptions(status)

export const mapClientsToSelect2Options = (clients: Client[]): Select2Option[] => clients.map(
  (client) => ({ id: client.id, text: client.name }),
)
