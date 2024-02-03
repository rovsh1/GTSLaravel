import { useAdminAPI } from '~api'

export type Car = {
  id: number
  mark: string
  model: string
  typeId: number
  passengersNumber: number
  bagsNumber: number
}

export const useGetSupplierCarsAPI = ({ supplierId }: { supplierId: number }) =>
  useAdminAPI({ supplierId }, () => `/supplier/${supplierId}/cars/list`)
    .get()
    .json<Car[]>()
