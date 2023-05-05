<script lang="ts" setup>
import { computed, ref } from 'vue'

import { flip, useFloating } from '@floating-ui/vue'

import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import { HotelID } from '~resources/lib/api/hotel/hotel'
import {
  HotelRoomQuotasStatusUpdateKind,
  HotelRoomQuotasStatusUpdateProps,
  useHotelRoomQuotasStatusUpdate,
} from '~resources/lib/api/hotel/quotas'
import { HotelRoomID } from '~resources/lib/api/hotel/room'
import { formatDateToAPIDate } from '~resources/lib/date'

import { useDayMenuButtonStatus } from './use-day-menu-button-status'

const props = defineProps<{
  menuRef: HTMLElement | null
  menuDayKey: string | null
  hotel: HotelID
  room: HotelRoomID
  dates: Date[] | null
}>()

const emit = defineEmits<{
  (event: 'done'): void
}>()

const reference = computed(() => props.menuRef)

const floating = ref(null)

const { floatingStyles } = useFloating(reference, floating, {
  middleware: [flip()],
  placement: 'bottom-start',
})

const selectedKind = ref<HotelRoomQuotasStatusUpdateKind | null>(null)

const updateProps = computed<HotelRoomQuotasStatusUpdateProps | null>(() => {
  const { hotel: hotelID, room: roomID, dates } = props
  const kind = selectedKind.value
  if (dates === null || kind === null) return null
  return {
    hotelID,
    roomID,
    kind,
    dates: dates.map((date) => formatDateToAPIDate(date)),
  }
})

const {
  execute,
  onFetchFinally,
  isFetching,
} = useHotelRoomQuotasStatusUpdate(updateProps)

onFetchFinally(() => {
  emit('done')
  selectedKind.value = null
})

const done = () => {
  execute()
}

const openDay = () => {
  selectedKind.value = 'open'
  done()
}

const closeDay = () => {
  selectedKind.value = 'close'
  done()
}

const resetDay = () => {
  // TODO backend
  done()
}

const {
  isLoading: isOpenLoading,
  isDisabled: isOpenDisabled,
} = useDayMenuButtonStatus({ kind: 'open', selectedKind, isFetching })

const {
  isLoading: isCloseLoading,
  isDisabled: isCloseDisabled,
} = useDayMenuButtonStatus({ kind: 'close', selectedKind, isFetching })
</script>
<template>
  <div ref="floating" :style="floatingStyles">
    <div class="btn-group-vertical list-group menu">
      <BootstrapButton
        size="small"
        severity="light"
        label="Открыть"
        :loading="isOpenLoading"
        :disabled="isOpenDisabled"
        @click="openDay"
      />
      <BootstrapButton
        size="small"
        severity="light"
        label="Закрыть"
        :loading="isCloseLoading"
        :disabled="isCloseDisabled"
        @click="closeDay"
      />
      <BootstrapButton
        size="small"
        severity="danger"
        label="Сбросить"
        @click="resetDay"
      />
    </div>
  </div>
</template>
<style scoped>
.menu {
  border: solid var(--bs-list-group-border-width) var(--bs-list-group-border-color);
  border-radius: var(--bs-list-group-border-radius);
}
</style>
