import { onMounted, ref } from 'vue'

import {
  getCancelConditions,
  getExistsCancelConditions,
  ServiceCancelConditions,
  updateCancelConditions,
  UpdateCancelConditionsPayload,
} from '~api/supplier/cancel-conditions/transfer'

export const useCancelConditions = (supplierId: number) => {
  const cancelConditions = ref<ServiceCancelConditions | null>(null)
  const isLoading = ref(false)
  const updatePayload = ref<UpdateCancelConditionsPayload>()
  const getDefaultCancelConditions = (): ServiceCancelConditions => ({
    noCheckInMarkup: { percent: '' },
    dailyMarkups: [{ percent: '', daysCount: '' }],
  })

  const { data: existsCancelConditions, execute: fetchExistsCancelConditions } = getExistsCancelConditions({ supplierId })

  const load = async (serviceId: number, seasonId: number, carId: number): Promise<void> => {
    isLoading.value = true
    const { data: response } = await getCancelConditions({ supplierId, seasonId, serviceId, carId })

    cancelConditions.value = response.value && Object.keys(response.value).length > 0
      ? response.value
      : getDefaultCancelConditions()

    updatePayload.value = { cancelConditions: cancelConditions.value, supplierId, seasonId, serviceId, carId }
    isLoading.value = false
  }

  const save = async (): Promise<void> => {
    if (!cancelConditions.value || !updatePayload.value) {
      throw new Error('Nothing to save. You should load before save.')
    }
    isLoading.value = true
    await updateCancelConditions({ ...updatePayload.value, cancelConditions: cancelConditions.value })
    fetchExistsCancelConditions()
    isLoading.value = false
  }

  onMounted(() => {
    fetchExistsCancelConditions()
  })

  return {
    isLoading,
    cancelConditions,
    existsCancelConditions,
    load,
    save,
  }
}
