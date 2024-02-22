import { useAdminAPI } from '~api'

export type CancelReasonResponse = {
  id: number
  name: string
  has_description: boolean
}

export const useCancelReasonListAPI = () =>
  useAdminAPI({ }, () => '/cancel-reason/list')
    .get()
    .json<CancelReasonResponse[]>()
