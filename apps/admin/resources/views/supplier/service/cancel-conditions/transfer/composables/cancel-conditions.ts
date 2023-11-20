import { ref } from 'vue'

import {
  getCancelConditions,
  ServiceCancelConditions,
  updateCancelConditions,
  UpdateCancelConditionsPayload,
} from '~api/supplier/cancel-conditions'

export const useCancelConditions = () => {
  const cancelConditions = ref<ServiceCancelConditions | null>(null)
  const isLoading = ref(false)
  const updatePayload = ref<UpdateCancelConditionsPayload>()
  const defaultCancelConditions = {
    noCheckInMarkup: { percent: 100 },
    dailyMarkups: [{ percent: 100, daysCount: 2 }],
  }

  const load = async (supplierId: number, serviceId: number, seasonId: number, carId: number): Promise<void> => {
    isLoading.value = true
    const { data: response } = await getCancelConditions({ supplierId, seasonId, serviceId, carId })

    cancelConditions.value = response.value && Object.keys(response.value).length > 0
      ? response.value
      : { ...defaultCancelConditions }

    updatePayload.value = { cancelConditions: cancelConditions.value, supplierId, seasonId, serviceId, carId }
    isLoading.value = false
  }

  const save = async (): Promise<void> => {
    if (!cancelConditions.value || !updatePayload.value) {
      throw new Error('Nothing to save. You should load before save.')
    }
    isLoading.value = true
    await updateCancelConditions({ ...updatePayload.value, cancelConditions: cancelConditions.value })
    isLoading.value = false
  }

  return {
    isLoading,
    cancelConditions,
    load,
    save,
  }
}
