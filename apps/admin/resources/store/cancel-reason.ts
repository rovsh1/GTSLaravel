import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import useLocalStorageCache from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { CancelReasonResponse, useCancelReasonListAPI } from '~api/cancel-reason'

export const useCancelReasonStore = defineStore('cancel-reasons', () => {
  const { data: dataFromStorage, existData, saveToLocalStorage } = useLocalStorageCache('cancel-reasons')
  const { data: cancelReasons, execute: fetchCities } = useCancelReasonListAPI()

  onMounted(async () => {
    if (existData.value) {
      cancelReasons.value = dataFromStorage.value as CancelReasonResponse[]
    } else {
      await fetchCities()
      saveToLocalStorage(cancelReasons.value)
    }
  })
  return {
    cancelReasons,
  }
})
