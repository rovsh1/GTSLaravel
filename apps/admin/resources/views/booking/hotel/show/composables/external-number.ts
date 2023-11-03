import { computed, ref } from 'vue'

import { useBookingStore } from '~resources/views/booking/hotel/show/store/booking'

import { updateExternalNumber as updateExternalNumberRequest } from '~api/booking/hotel'
import { ExternalNumber, ExternalNumberType, ExternalNumberTypeEnum } from '~api/booking/hotel/details'

export const useExternalNumber = (bookingID: number) => {
  const bookingStore = useBookingStore()

  const isExternalNumberValid = ref(true)
  const isExternalNumberChanged = ref<boolean>(false)
  const isUpdateExternalNumberFetching = ref(false)

  const externalNumberData = ref<ExternalNumber>({
    type: ExternalNumberTypeEnum.HotelBookingNumber,
    number: null,
  })

  const externalNumberType = computed<ExternalNumberType>({
    get: () => {
      if (isExternalNumberChanged.value) {
        return externalNumberData.value.type
      }
      return bookingStore.booking?.details?.externalNumber?.type || ExternalNumberTypeEnum.HotelBookingNumber
    },
    set: (value: ExternalNumberType) => {
      isExternalNumberChanged.value = true
      externalNumberData.value.type = Number(value)
      externalNumberData.value.number = null
    },
  })

  const externalNumber = computed<string | null>({
    get: () => {
      if (isExternalNumberChanged.value) {
        return externalNumberData.value.number
      }
      return bookingStore.booking?.details?.externalNumber?.number || null
    },
    set: (value: string | null): void => {
      isExternalNumberChanged.value = true
      externalNumberData.value.number = value
    },
  })

  const hideValidation = (): void => {
    isExternalNumberValid.value = true
    isExternalNumberChanged.value = false
  }

  const validateExternalNumber = (): boolean => {
    // @todo валидация перед переходом на статус "Подтверждена" для админки отелей.
    const type = externalNumberType.value
    const number = externalNumber.value

    const isHotelNumberType = type === ExternalNumberTypeEnum.HotelBookingNumber
    const isEmptyNumber = (!number || number?.trim().length === 0)
    if (isHotelNumberType && isEmptyNumber) {
      isExternalNumberValid.value = false
      return false
    }
    isExternalNumberValid.value = true
    return true
  }

  const updateExternalNumber = async (): Promise<boolean> => {
    if (!validateExternalNumber()) {
      return false
    }
    isUpdateExternalNumberFetching.value = true
    await updateExternalNumberRequest({ bookingID, ...externalNumberData.value })
    await bookingStore.fetchBooking()
    isUpdateExternalNumberFetching.value = false
    return true
  }

  return {
    isExternalNumberValid,
    isExternalNumberChanged,
    isUpdateExternalNumberFetching,
    externalNumberType,
    externalNumber,
    updateExternalNumber,
    validateExternalNumber,
    hideValidation,
  }
}
