import { computed, Ref } from 'vue'

import { HotelRoomQuotasStatusUpdateKind } from '~resources/lib/api/hotel/quotas/status'

type UseDayMenuButtonStatusParams = {
  kind: HotelRoomQuotasStatusUpdateKind
  selectedKind: Ref<HotelRoomQuotasStatusUpdateKind | null>
  isFetching: Ref<boolean>
}

type ButtonStatus = (params: UseDayMenuButtonStatusParams) => boolean

const isButtonLoading: ButtonStatus = ({ kind, selectedKind, isFetching }) =>
  selectedKind.value === kind && isFetching.value

const isButtonDisabled: ButtonStatus = ({ kind, selectedKind, isFetching }) =>
  selectedKind.value !== kind && isFetching.value

export const useDayMenuButtonStatus = (params: UseDayMenuButtonStatusParams) => ({
  isLoading: computed(() => isButtonLoading(params)),
  isDisabled: computed(() => isButtonDisabled(params)),
})
