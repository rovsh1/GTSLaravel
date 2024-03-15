<script lang="ts" setup>
import { computed, nextTick, ref, watch } from 'vue'

import { useToggle } from '@vueuse/core'
import BaseDialog from 'gts-components/Base/BaseDialog'
import BaseLayout from 'gts-components/Base/BaseLayout'
import EmptyData from 'gts-components/Base/EmptyData'
import OverlayLoading from 'gts-components/Base/OverlayLoading'
import BootstrapButton from 'gts-components/Bootstrap/BootstrapButton/BootstrapButton'

import QuotasFilters from './components/QuotasFilters/QuotasFilters.vue'
import QuotasStatusSwitcher from './components/QuotasStatusSwitcher.vue'
import RoomQuotasComponent from './components/RoomQuotas.vue'

import '~assets/support/jquery'
import '~assets/support/select2'

import { Day, FiltersPayload, HotelResponse, HotelRoom, HotelRoomQuotasStatusUpdatePayload,
  HotelRoomQuotasUpdatePayload, Month, QuotasStatusUpdatePayload, RoomQuota, UseHotelRooms } from './components/lib/types'
import { defaultFiltersPayload } from './components/QuotasFilters/lib'

const props = defineProps<{
  isHotelFetching: boolean
  isHotelRoomsFetching: boolean
  isUpdateHotelRoomQuotasBatch: boolean
  waitLoadAndRedrawData: boolean
  hotel: HotelResponse | null
  rooms: HotelRoom[]
  days: Day[]
  months: Month[]
  allQuotas: Map<string, RoomQuota>
  isHotelRoomQuotasUpdateFetching: boolean
  updatedQuotasRoomId: number | null
  isUpdateRoomQuotasStatus: boolean
  isSuccessUpdateRoomQuotasStatus: boolean
}>()

const emit = defineEmits<{
  (event: 'fetchHotelQuotas', filters: FiltersPayload): void
  (event: 'updateHotelRoomQuotasBatch', batchfilters: QuotasStatusUpdatePayload, filters: FiltersPayload): void
  (event: 'roomQuotasUpdate', updatedQuotas: HotelRoomQuotasUpdatePayload): void
  (event: 'roomQuotasStatusUpdate', quotasStatusPayload: HotelRoomQuotasStatusUpdatePayload | null): void
}>()

const openingDayMenuRoomId = ref<number | null>(null)

const [isOpenedOpenCloseQuotasModal, toggleModalOpenCloseQuotas] = useToggle()

const hotel = computed<HotelResponse | null>(() => props.hotel)
const rooms = computed<UseHotelRooms>(() => props.rooms)
const isHotelFetching = computed<boolean>(() => props.isHotelFetching)
const isHotelRoomsFetching = computed<boolean>(() => props.isHotelRoomsFetching)

const isUpdateHotelRoomQuotasBatch = computed<boolean>(() => props.isUpdateHotelRoomQuotasBatch)

const waitLoadAndRedrawData = computed<boolean>(() => props.waitLoadAndRedrawData)
const quotasPeriod = computed<Day[]>(() => props.days)
const quotasPeriodMonths = computed<Month[]>(() => props.months)
const allQuotas = computed<Map<string, RoomQuota>>(() => props.allQuotas)

const filtersQuotasStatusBatchPayload = ref<QuotasStatusUpdatePayload | null>(null)
const filtersPayload = ref<FiltersPayload>(defaultFiltersPayload)
const waitSwitchRooms = ref<boolean>(false)
const updatedRoomID = ref<number | null>(null)

const fetchHotelQuotas = () => {
  emit('fetchHotelQuotas', filtersPayload.value)
}

watch(filtersPayload, () => {
  updatedRoomID.value = null
  fetchHotelQuotas()
})

const editable = ref(false)

const activeRoomIDs = ref<number[]>([])

watch(editable, (value) => {
  if (value === false) {
    fetchHotelQuotas()
  }
})

watch(() => props.waitLoadAndRedrawData, (value) => {
  if (!value) updatedRoomID.value = null
})

watch(() => props.isUpdateHotelRoomQuotasBatch, (value) => {
  if (!value) toggleModalOpenCloseQuotas(false)
})

const handleFilters = (value: FiltersPayload) => {
  filtersPayload.value = value
}

const switchRooms = (value: number[]) => {
  activeRoomIDs.value = value
  nextTick(() => {
    waitSwitchRooms.value = false
  })
}

const handleUpdateQuotasBatch = async () => {
  if (!filtersQuotasStatusBatchPayload.value || isUpdateHotelRoomQuotasBatch.value) return
  emit('updateHotelRoomQuotasBatch', filtersQuotasStatusBatchPayload.value, filtersPayload.value)
}
</script>

<template>
  <BaseLayout :loading="isHotelFetching || isHotelRoomsFetching">
    <template #title>
      <div class="title">{{ hotel?.name ?? '' }}</div>
    </template>
    <template #header-controls>
      <BootstrapButton
        :label="editable ? 'Готово' : 'Редактировать'"
        :start-icon="editable ? 'done' : 'edit'"
        severity="primary"
        :disabled="rooms === null || waitLoadAndRedrawData || waitSwitchRooms"
        @click="editable = !editable"
      />
      <BootstrapButton
        label="Открыть/закрыть квоты"
        severity="link"
        :disabled="rooms === null || waitLoadAndRedrawData || waitSwitchRooms"
        @click="toggleModalOpenCloseQuotas()"
      />
    </template>
    <div class="quotasBody">
      <QuotasFilters
        v-if="rooms"
        :rooms="rooms"
        :loading="waitLoadAndRedrawData || waitSwitchRooms"
        @submit="value => handleFilters(value)"
        @switch-room="(value) => switchRooms(value)"
        @wait-switch-room="waitSwitchRooms = true"
      />
      <div v-if="hotel === null">
        <EmptyData>
          Не удалось найти данные для отеля.
        </EmptyData>
      </div>
      <div v-else-if="rooms === null">
        <EmptyData>
          Не удалось найти номера для этого отеля. <a v-if="hotel?.id" :href="`/hotels/${hotel.id}/rooms/create`">Добавить номер</a>
        </EmptyData>
      </div>
      <div v-else class="quotasTables">
        <OverlayLoading v-if="waitSwitchRooms" />
        <template v-for="room in rooms" :key="room.id">
          <div v-if="activeRoomIDs.includes(room.id)" style="position: relative;">
            <OverlayLoading v-if="(updatedRoomID === null) ? waitLoadAndRedrawData : false" />
            <RoomQuotasComponent
              :hotel="hotel"
              :room="room"
              :days="quotasPeriod"
              :months="quotasPeriodMonths"
              :all-quotas="allQuotas"
              :editable="editable"
              :reload-active-room="(updatedRoomID === room.id) ? waitLoadAndRedrawData : false"
              :opening-day-menu-room-id="openingDayMenuRoomId"
              :is-hotel-room-quotas-update-fetching="isHotelRoomQuotasUpdateFetching"
              :updated-quotas-room-id="updatedQuotasRoomId"
              :is-success-update-room-quotas-status="isSuccessUpdateRoomQuotasStatus"
              :is-update-room-quotas-status="isUpdateRoomQuotasStatus"
              @open-day-menu-in-another-room="(value: number | null) => {
                openingDayMenuRoomId = value
              }"
              @update="(updatedRoomIDParam: number) => {
                updatedRoomID = updatedRoomIDParam
                fetchHotelQuotas()
              }"
              @room-quotas-update="(value: HotelRoomQuotasUpdatePayload) => {
                emit('roomQuotasUpdate', value)
              }"
              @room-quotas-status-update="value => emit('roomQuotasStatusUpdate', value)"
            />
          </div>
        </template>
      </div>
    </div>
  </BaseLayout>
  <BaseDialog
    :opened="isOpenedOpenCloseQuotasModal as boolean"
    :auto-width="true"
    :click-outside-ignore="['.litepicker']"
    @keydown.enter="handleUpdateQuotasBatch"
    @close="toggleModalOpenCloseQuotas(false)"
  >
    <template #title>
      Открытие/закрытие квот
    </template>
    <QuotasStatusSwitcher
      v-if="rooms && isOpenedOpenCloseQuotasModal"
      :initial-period="[filtersPayload.dateFrom, filtersPayload.dateTo]"
      :re-init-form="isOpenedOpenCloseQuotasModal"
      :rooms="rooms"
      :disabled="isUpdateHotelRoomQuotasBatch"
      @submit="value => filtersQuotasStatusBatchPayload = value"
    />
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="!filtersQuotasStatusBatchPayload || isUpdateHotelRoomQuotasBatch"
        @click="handleUpdateQuotasBatch"
      >
        Применить
      </button>
      <button :disabled="isUpdateHotelRoomQuotasBatch" class="btn btn-cancel" type="button" @click="toggleModalOpenCloseQuotas(false)">Скрыть</button>
    </template>
  </BaseDialog>
</template>

<style lang="scss" scoped>
.quotasBody {
  display: flex;
  flex-flow: column;
  gap: 2em;
}

.quotasTables {
  position: relative;
  display: flex;
  flex-flow: column;
  gap: 2em;
}

#hotel-quotas-wrapper {
  position: relative;
}
</style>
