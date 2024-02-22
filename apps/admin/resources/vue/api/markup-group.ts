import { useAdminAPI } from '~api'

export type MarkupGroupResponse = {
  id: number
  name: string
  value: number
  type: number
}

export const useMarkupGroupListAPI = () =>
  useAdminAPI({}, () => '/markup-group/list')
    .get()
    .json<MarkupGroupResponse[]>()
