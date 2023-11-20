import { ref } from 'vue'

import { getCancelConditions, ServiceCancelConditions } from '~api/supplier/cancel-conditions'

export const useCancelConditions = () => {
  const cancelConditions = ref<ServiceCancelConditions | null>(null)
  const isLoading = ref(false)

  const load = async (supplierId: number, serviceId: number, seasonId: number, carId: number): Promise<void> => {
    isLoading.value = true
    const { data: response } = await getCancelConditions({ supplierId, seasonId, serviceId, carId })
    cancelConditions.value = response.value
    isLoading.value = false
  }

  const save = async (): Promise<void> => {
    if (!cancelConditions.value) {
      throw new Error('Nothing to save. You should load before save.')
    }
    // @todo взять данные для запроса: supplierId: number, serviceId: number, seasonId: number, carId: number
    // await updateCancelConditions({
    //   cancelConditions: cancelConditions.value,
    // })
    console.log('save', cancelConditions.value)
  }

  return {
    isLoading,
    cancelConditions,
    load,
    save,
  }
}
