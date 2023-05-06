<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import { flip, useFloating } from '@floating-ui/vue'

import { HotelID } from '~api/hotel/get'
import {
  HotelRoomQuotasStatusUpdateKind,
  HotelRoomQuotasStatusUpdateProps, useHotelRoomQuotasStatusUpdate,
} from '~api/hotel/quotas/status'
import { HotelRoomID } from '~api/hotel/room'

import { formatDateToAPIDate } from '~lib/date'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'

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

const { floatingStyles, placement } = useFloating(reference, floating, {
  middleware: [flip()],
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
  data,
  isFetching,
} = useHotelRoomQuotasStatusUpdate(updateProps)

watch(data, (value) => {
  if (value === null || !value.success) return
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
  selectedKind.value = 'reset'
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

const {
  isLoading: isResetLoading,
  isDisabled: isResetDisabled,
} = useDayMenuButtonStatus({ kind: 'reset', selectedKind, isFetching })
</script>
<template>
  <div ref="floating" :style="floatingStyles">
    <div
      class="fakeTooltip tooltip bs-tooltip-auto"
      :data-popper-placement="placement"
    >
      <div class="fakeTooltipArrow tooltip-arrow" />
    </div>
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
        :loading="isResetLoading"
        :disabled="isResetDisabled"
        @click="resetDay"
      />
    </div>
  </div>
</template>
<style lang="scss" scoped>
.menu {
  border: solid var(--bs-list-group-border-width) var(--bs-list-group-border-color);
  border-radius: var(--bs-list-group-border-radius);
  box-shadow: 0 0 2em rgba(black, 0.3);
}

.fakeTooltip {
  --bs-tooltip-bg: var(--bs-body-bg);

  opacity: 1;
}

.fakeTooltipArrow {
  position: absolute;
  left: calc(50% - var(--bs-tooltip-arrow-width) / 2);
  z-index: 1;
}
</style>
