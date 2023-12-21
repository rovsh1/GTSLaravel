import { onMounted } from 'vue'

import { defineStore } from 'pinia'

import { TTLValues, useLocalStorageCache } from '~resources/lib/locale-storage-cache/locale-storage-cache'

import { CancelReasonResponse, useCancelReasonListAPI } from '~api/cancel-reason'

export const useCancelReasonStore = defineStore('cancel-reasons', () => {
  const { hasData, setData, getData } = useLocalStorageCache('cancel-reasons', TTLValues.DAY)
  const { data: cancelReasons, execute: fetchCancelReasons } = useCancelReasonListAPI()

  onMounted(async () => {
    if (hasData()) {
      cancelReasons.value = getData() as CancelReasonResponse[]
    } else {
      await fetchCancelReasons()
      setData(cancelReasons.value)
    }
  })
  return {
    cancelReasons,
  }
})
