import { useAdminAPI } from '~api/index'

export interface AdministratorResponse {
  id: number
  presentation: string
  name: string | null
  email: string | null
}

export const useAdministratorGetAPI = () =>
  useAdminAPI({ }, () => '/administrator/get')
    .get()
    .json<AdministratorResponse[]>()
