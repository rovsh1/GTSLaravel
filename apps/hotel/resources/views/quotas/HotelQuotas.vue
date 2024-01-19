<script lang="ts" setup>
import { computed, nextTick, ref, watch } from 'vue'

import { useToggle } from '@vueuse/core'

import { useUpdateHotelRoomQuotasBatch } from '~resources/api/hotel/quotas/batch'
import { formatDateToAPIDate } from '~resources/lib/date'

import { HotelResponse, useHotelGetAPI } from '~api/hotel/get'
import { useHotelQuotasAPI } from '~api/hotel/quotas/list'
import { UseHotelRooms, useHotelRoomsListAPI } from '~api/hotel/rooms'

import BaseDialog from '~components/BaseDialog.vue'
import BaseLayout from '~components/BaseLayout.vue'
import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import EmptyData from '~components/EmptyData.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

import QuotasFilters from './components/QuotasFilters/QuotasFilters.vue'
import QuotasStatusSwitcher from './components/QuotasStatusSwitcher.vue'
import RoomQuotasComponent from './components/RoomQuotas.vue'

import { Day, getRoomQuotas, Month, RoomQuota } from './components/lib'
import { QuotasStatusUpdatePayload } from './components/lib/types'
import { defaultFiltersPayload, FiltersPayload } from './components/QuotasFilters/lib'

const openingDayMenuRoomId = ref<number | null>(null)

const [isOpenedOpenCloseQuotasModal, toggleModalOpenCloseQuotas] = useToggle()

const {
  data: hotelData,
  execute: fetchHotel,
  isFetching: isHotelFetching,
} = useHotelGetAPI()

fetchHotel()

const hotel = computed<HotelResponse | null>(() => hotelData.value)

const {
  data: roomsData,
  execute: fetchHotelRoomsAPI,
  isFetching: isHotelRoomsFetching,
} = useHotelRoomsListAPI()

const rooms = computed<UseHotelRooms>(() => roomsData.value)

fetchHotelRoomsAPI()

const filtersQuotasStatusBatchPayload = ref<QuotasStatusUpdatePayload | null>(null)
const filtersPayload = ref<FiltersPayload>(defaultFiltersPayload)
const waitLoadAndRedrawData = ref<boolean>(false)
const waitSwitchRooms = ref<boolean>(false)
const updatedRoomID = ref<number | null>(null)

const {
  execute: fetchHotelQuotas,
  data: hotelQuotas,
} = useHotelQuotasAPI(computed(() => {
  const { dateFrom, dateTo, availability } = filtersPayload.value
  return {
    dateFrom: formatDateToAPIDate(dateFrom),
    dateTo: formatDateToAPIDate(dateTo),
    roomID: undefined,
    availability: availability ?? undefined,
  }
}))

const {
  execute: executeUpdateHotelRoomQuotasBatch,
  isFetching: isUpdateHotelRoomQuotasBatch,
} = useUpdateHotelRoomQuotasBatch(computed(() => {
  if (!filtersQuotasStatusBatchPayload.value) return null
  const { dateFrom, dateTo, selectedRoomsID, daysWeekSelected, action } = filtersQuotasStatusBatchPayload.value
  return {
    dateFrom: formatDateToAPIDate(dateFrom),
    dateTo: formatDateToAPIDate(dateTo),
    weekDays: daysWeekSelected,
    roomIds: selectedRoomsID,
    action,
  }
}))

const quotasPeriod = ref<Day[]>([])
const quotasPeriodMonths = ref<Month[]>([])
const allQuotas = ref<Map<string, RoomQuota>>(new Map<string, RoomQuota>([]))

const fetchHotelQuotasWrapper = async () => {
  waitLoadAndRedrawData.value = true
  try {
    await fetchHotelQuotas()
    const roomsQuotasAccumalationData = getRoomQuotas({
      filters: filtersPayload.value,
      quotas: hotelQuotas.value,
    })
    const { period, months, quotas } = roomsQuotasAccumalationData
    quotasPeriod.value = period
    quotasPeriodMonths.value = months
    allQuotas.value = quotas
    nextTick(() => {
      waitLoadAndRedrawData.value = false
      updatedRoomID.value = null
    })
  } catch (error) {
    nextTick(() => {
      waitLoadAndRedrawData.value = false
      updatedRoomID.value = null
    })
  }
}

fetchHotelQuotasWrapper()

watch(filtersPayload, () => {
  updatedRoomID.value = null
  fetchHotelQuotasWrapper()
})

const editable = ref(false)

const activeRoomIDs = ref<number[]>([])

watch(editable, (value) => {
  if (value === false) {
    fetchHotelQuotasWrapper()
  }
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
  await executeUpdateHotelRoomQuotasBatch()
  fetchHotelQuotasWrapper()
  toggleModalOpenCloseQuotas(false)
}

</script>
<template>
  <div id="hotel-quotas-wrapper">
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
            Не удалось найти данные о отеле.
          </EmptyData>
        </div>
        <div v-else-if="rooms === null">
          <EmptyData>
            Не удалось найти номера у отеля.
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
                @open-day-menu-in-another-room="(value: number | null) => {
                  openingDayMenuRoomId = value
                }"
                @update="(updatedRoomIDParam: number) => {
                  updatedRoomID = updatedRoomIDParam
                  fetchHotelQuotasWrapper()
                }"
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
        v-if="rooms"
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
  </div>
</template>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

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
