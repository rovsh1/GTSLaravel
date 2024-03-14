<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'

import { flip, useFloating } from '@floating-ui/vue'
import BootstrapButton from 'gts-components/Bootstrap/BootstrapButton/BootstrapButton'

import {
  HotelRoomQuotasStatusUpdateKind,
  HotelRoomQuotasStatusUpdatePayload,
} from '../lib/types'
import { useDayMenuButtonStatus } from './use-day-menu-button-status'

const props = defineProps<{
  menuRef: HTMLElement | null
  menuDayKey: string | null
  hotel: number
  room: number
  dates: string[] | null
  isUpdateRoomQuotasStatus: boolean
  isSuccessUpdateRoomQuotasStatus: boolean
}>()

const emit = defineEmits<{
  (event: 'done'): void
  (event: 'set-menu-element', element: HTMLElement | null): void
  (event: 'roomQuotasStatusUpdate', quotasStatusPayload: HotelRoomQuotasStatusUpdatePayload | null): void
}>()

const reference = computed(() => props.menuRef)

const floating = ref(null)

const { floatingStyles, placement } = useFloating(reference, floating, {
  middleware: [flip()],
})

const selectedKind = ref<HotelRoomQuotasStatusUpdateKind | null>(null)

const updateProps = computed<HotelRoomQuotasStatusUpdatePayload | null>(() => {
  const { hotel: hotelID, room: roomID, dates } = props
  const kind = selectedKind.value
  if (dates === null || kind === null) return null

  return {
    hotelID,
    roomID,
    kind,
    dates,
  }
})

const isUpdateRoomQuotasStatus = computed<boolean>(() => props.isUpdateRoomQuotasStatus)

watch(() => props.isSuccessUpdateRoomQuotasStatus, (value) => {
  if (!value) return
  emit('done')
  selectedKind.value = null
})

const done = () => {
  emit('roomQuotasStatusUpdate', updateProps.value)
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
} = useDayMenuButtonStatus({ kind: 'open', selectedKind, isFetching: isUpdateRoomQuotasStatus })

const {
  isLoading: isCloseLoading,
  isDisabled: isCloseDisabled,
} = useDayMenuButtonStatus({ kind: 'close', selectedKind, isFetching: isUpdateRoomQuotasStatus })

const {
  isLoading: isResetLoading,
  isDisabled: isResetDisabled,
} = useDayMenuButtonStatus({ kind: 'reset', selectedKind, isFetching: isUpdateRoomQuotasStatus })

onMounted(() => {
  emit('set-menu-element', floating.value)
})
</script>
<template>
  <div ref="floating" class="menu-floating" :style="floatingStyles">
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
        @mousedown.prevent
      />
      <BootstrapButton
        size="small"
        severity="light"
        label="Закрыть"
        :loading="isCloseLoading"
        :disabled="isCloseDisabled"
        @click="closeDay"
        @mousedown.prevent
      />
      <BootstrapButton
        size="small"
        severity="danger"
        label="Сбросить"
        :loading="isResetLoading"
        :disabled="isResetDisabled"
        @click="resetDay"
        @mousedown.prevent
      />
    </div>
  </div>
</template>
<style lang="scss" scoped>
.menu-floating {
  z-index: 350;
}

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
