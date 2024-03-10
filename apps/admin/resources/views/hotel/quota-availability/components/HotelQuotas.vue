<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'

import { formatDateToAPIDate } from 'gts-common/helpers/date'
import OverlayLoading from 'gts-components/Base/OverlayLoading'
import { DateTime } from 'luxon'

import { Day, Month, RoomQuota, RoomQuotaStatus } from '~resources/views/hotel/quotas/components/lib'
import { HotelInfo, QuotaAvailability, QuotaInfo } from '~resources/vue/api/hotel/quotas/availability'

import { useHotelQuotasAPI } from '~api/hotel/quotas/list'
import { UseHotelRooms, useHotelRoomsListAPI } from '~api/hotel/rooms'

import { getRoomQuotas } from './lib'
import { FiltersPayload } from './QuotaAvailabilityFilters/lib'

type HotelQuotasAccumulationData = {
  hotel: HotelInfo
  hotelQuotasCount: Map<string, QuotaInfo>
  hotelQuotas: Map<string, RoomQuota> | null
  isShowHotelRooms: boolean
  rooms: UseHotelRooms
}

const props = withDefaults(defineProps<{
  hotelsQuotas: QuotaAvailability[] | null
  days: Day[]
  months: Month[]
  filters: FiltersPayload | null
}>(), {

})

const selectedHotelID = ref<number | null>(null)

const monthsLocal = computed<Month[]>(() => props.months)

const daysLocal = computed<Day[]>(() => props.days)

const hotelsQuotasLocal = ref<HotelQuotasAccumulationData[]>([])

const initHotelsQuotasLocal = () => {
  const hotelsQuotasAccumulation: HotelQuotasAccumulationData[] = []
  const hotelQuotasMap = new Map<string, QuotaInfo>()

  props.hotelsQuotas?.forEach((hotelQuota) => {
    hotelQuota.quotas.forEach((quota) => {
      const key = DateTime.fromFormat(quota.date, 'yyyy-MM-dd')
        .toJSDate()
        .getTime()
        .toString()
      hotelQuotasMap.set(`${key}-${hotelQuota.hotel.id}`, {
        ...quota,
      })
    })
    hotelsQuotasAccumulation.push({
      hotel: hotelQuota.hotel,
      hotelQuotasCount: hotelQuotasMap,
      isShowHotelRooms: false,
      hotelQuotas: null,
      rooms: null,
    })
  })
  hotelsQuotasLocal.value = hotelsQuotasAccumulation
}

watch(() => props.hotelsQuotas, () => {
  initHotelsQuotasLocal()
})

const {
  execute: fetchHotelQuotas,
  data: hotelQuotas,
  isFetching: isFetchingHotelQuotas,
} = useHotelQuotasAPI(computed(() => {
  const { dateFrom, dateTo } = props.filters as FiltersPayload
  return {
    hotelID: selectedHotelID.value as number,
    dateFrom: formatDateToAPIDate(dateFrom),
    dateTo: formatDateToAPIDate(dateTo),
  }
}))

const {
  data: rooms,
  execute: fetchHotelRooms,
  isFetching: isHotelRoomsFetching,
} = useHotelRoomsListAPI(computed(() => (selectedHotelID.value ? { hotelID: selectedHotelID.value } : null)))

const getHotelDayDataByPropertyName = (key: string, hotelDayData: Map<string, QuotaInfo>, property: keyof QuotaInfo): any => {
  let value: QuotaInfo | undefined | null = null
  if (hotelDayData.has(key)) {
    value = hotelDayData.get(key)
    if (value) {
      return value[property] !== null && value[property] !== undefined ? value[property] : null
    }
  }
  return null
}

const getHotelQuotasDataByPropertyName = (
  key: string,
  hotelQuotasData: Map<string, RoomQuota>,
  property: keyof RoomQuota,
): any => {
  let value: RoomQuota | undefined | null = null
  if (hotelQuotasData.has(key)) {
    value = hotelQuotasData.get(key)
    if (value) {
      return value[property] !== null && value[property] !== undefined ? value[property] : null
    }
  }
  return null
}

const dayCellClassNameByRoomQuotaStatus: Record<RoomQuotaStatus, string> = {
  opened: 'isOpened',
  closed: 'isClosed',
  warning: 'isWarning',
}

const dayQuotaCellClassName = (status: RoomQuotaStatus | null) =>
  ['dayQuotaCell', status !== null && dayCellClassNameByRoomQuotaStatus[status]]

const getHotelDayQuotaStatus = (value?: number | null) => {
  if (value !== undefined && value !== null) {
    if (value === 0) return 'closed'
    return 'opened'
  }
  return null
}

const toogleHotelRooms = async (hotelData: HotelQuotasAccumulationData, hotelID: number) => {
  const source = hotelData
  if (!source.isShowHotelRooms) {
    selectedHotelID.value = hotelID
    await Promise.all([fetchHotelRooms(), fetchHotelQuotas()])
    source.rooms = rooms.value
    source.hotelQuotas = getRoomQuotas(hotelQuotas.value)
    source.isShowHotelRooms = true
  } else {
    source.isShowHotelRooms = false
  }
}

onMounted(initHotelsQuotasLocal)

</script>

<template>
  <div class="quotasTables">
    <OverlayLoading v-if="isHotelRoomsFetching || isFetchingHotelQuotas" style="z-index: 999;" />
    <div class="quotasTable card">
      <table class="card-body p-0">
        <thead class="floating-header">
          <tr class="floating-row">
            <th rowspan="2" class="headingCell">
              Отель / Тип номера
            </th>
            <td class="otherCell" rowspan="2" />
            <td
              v-for="{ monthKey, daysCount, monthName } in monthsLocal"
              :key="monthKey"
              :colspan="daysCount"
              :class="[monthsLocal.length > 1 ? 'month-splitter' : '']"
            >
              <div class="monthName">
                <span><b>{{ monthName }}</b></span>
              </div>
            </td>
          </tr>
          <tr>
            <template
              v-for="{ key, isHoliday, dayOfWeek, dayOfMonth, isLastDayInMonth } in daysLocal"
              :key="key"
            >
              <td class="dayCell" :class="{ isHoliday: isHoliday, 'month-splitter': isLastDayInMonth }" tabindex="0">
                <div class="dayOfWeek">{{ dayOfWeek }}</div>
                <div class="dayOfMonth"><strong>{{ dayOfMonth }}</strong></div>
              </td>
            </template>
          </tr>
        </thead>
        <tbody>
          <template v-for="hotelQuota in hotelsQuotasLocal" :key="hotelQuota.hotel.id">
            <tr
              class="typeHeadingRow"
              @click="toogleHotelRooms(hotelQuota, hotelQuota.hotel.id)"
            >
              <th class="headingCell">
                {{ hotelQuota.hotel.name }}
              </th>
              <td class="otherCell">квоты</td>
              <template v-for="{ key, isLastDayInMonth } in daysLocal" :key="key">
                <td
                  class="quotaCell"
                  :class="[isLastDayInMonth ? 'month-splitter' : '', dayQuotaCellClassName(getHotelDayQuotaStatus(
                    getHotelDayDataByPropertyName(`${key}-${hotelQuota.hotel.id}`, hotelQuota.hotelQuotasCount, 'countAvailable')))]"
                >
                  {{ getHotelDayDataByPropertyName(`${key}-${hotelQuota.hotel.id}`, hotelQuota.hotelQuotasCount, 'countAvailable') }}
                </td>
              </template>
            </tr>
            <template v-if="hotelQuota.isShowHotelRooms && hotelQuota.rooms?.length">
              <tr
                v-for="(room, index) in hotelQuota.rooms"
                :key="`${room.hotelID}-${room.id}`"
                :class="{ 'last-hotel-room-row': index === hotelQuota.rooms.length - 1 }"
                class="room-row"
              >
                <th class="headingCell">
                  <div>
                    {{ room.name }}
                  </div>
                  <div>
                    Кол-во гостей: <span>{{ room.guestsCount }}</span>
                  </div>
                  <div>
                    Кол-во номеров: <span>{{ room.roomsNumber }}</span>
                  </div>
                </th>
                <td class="otherCell">
                  <div>
                    квоты - продано
                  </div>
                  <div>
                    релиз дни
                  </div>
                </td>
                <template v-for="{ key, isLastDayInMonth } in daysLocal" :key="key">
                  <template v-if="hotelQuota.hotelQuotas">
                    <td
                      class="quotaCell"
                      :class="[dayQuotaCellClassName(
                        (getHotelQuotasDataByPropertyName(`${key}-${room.id}`, hotelQuota.hotelQuotas, 'status') === 'opened'
                          && getHotelQuotasDataByPropertyName(`${key}-${room.id}`, hotelQuota.hotelQuotas, 'quota') === 0
                          ? 'warning' : getHotelQuotasDataByPropertyName(`${key}-${room.id}`, hotelQuota.hotelQuotas, 'status'))), isLastDayInMonth ? 'month-splitter' : '']"
                    >
                      <template v-if="hotelQuota.hotelQuotas.has(`${key}-${room.id}`)">
                        <div>
                          {{ getHotelQuotasDataByPropertyName(`${key}-${room.id}`, hotelQuota.hotelQuotas, 'quota') }} -
                          {{ getHotelQuotasDataByPropertyName(`${key}-${room.id}`, hotelQuota.hotelQuotas, 'sold') }}
                        </div>
                        <div>
                          {{ getHotelQuotasDataByPropertyName(`${key}-${room.id}`, hotelQuota.hotelQuotas, 'releaseDays') }}
                        </div>
                      </template>
                    </td>
                  </template>
                  <td v-else />
                </template>
              </tr>
            </template>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;
@use 'shared' as shared;

.quotasTables {
  position: relative;
  display: flex;
  flex-flow: column;
  gap: 1em;
  overflow: auto;
  max-height: 700px;

  .floating-header {
    position: sticky;
    top: -10px;
    z-index: 3;
    background-color: #fff;
  }
}

.quotasTable {
  --cell-width: 6em;

  overflow: auto;
  padding: 10px 0;

  table {
    border-spacing: 0;
    border-collapse: separate;
  }
}

th {
  position: sticky;
  left: 0;
  background-color: bs.$body-bg;
  font-weight: normal;
  white-space: nowrap;
}

%cell {
  @include shared.cell;
}

.dayCell {
  @extend %cell;

  min-width: var(--cell-width);
  text-align: center;

  &.isHoliday {
    color: bs.$error;
  }
}

.quotaCell {
  vertical-align: middle;
  padding: 0 0.5rem;
  font-size: 12px;
  text-align: center;
}

%data-cell {
  @include shared.data-cell;
}

.dataCell {
  @extend %data-cell;
}

%heading-cell {
  @extend %cell;

  padding-left: 1.5em;
  text-align: left;
  white-space: nowrap;
}

.headingCell {
  @extend %heading-cell;

  z-index: 1;
  min-width: 270px;
  max-width: 270px;
  white-space: normal;
}

.otherCell {
  min-width: 135px;
  max-width: 170px;
  padding: 0 0.5rem;
  font-size: 12px;
  text-align: center;
}

%cell-title-line {
  text-transform: capitalize;
}

.dayOfWeek {
  @extend %cell-title-line;
}

.dayOfMonth {
  font-size: 1.1em
}

.monthName {
  @extend %cell-title-line;

  padding: 0 0.5rem;
  text-align: left;
}

.typeHeadingRow {
  th,
  td {
    border-top: 1px solid lightgray;
    cursor: pointer;
  }

  &:hover {
    th,
    td:not(.quotaCell) {
      background-color: lightgray;
    }
  }
}

.roomTypeHeadingCell {
  padding-right: 1em;
}

.room-row {
  &:hover {
    th,
    td:not(.quotaCell) {
      background-color: lightgray;
    }
  }

  .headingCell {
    padding-left: 2rem;
    font-size: 11px;

    div:first-child {
      margin-bottom: 0.5rem;
    }

    div span {
      color: red;
    }
  }

  .quotaCell {
    div:last-child {
      position: relative;

      &::after {
        content: '';
        position: absolute;
        top: 0;
        left: 20%;
        z-index: 0;
        display: block;
        width: 60%;
        height: 1px;
        background: #ccc;
      }
    }
  }

  .otherCell,
  .quotaCell {
    div {
      padding: 5px 0;
    }
  }

  th,
  td {
    border-bottom: 1px solid var(--bs-border-color-translucent);
    cursor: default;
  }
}

.last-hotel-room-row {
  th,
  td {
    border-bottom: none;
  }
}

.dayQuotaCell {
  max-width: var(--cell-width);

  &.isOpened {
    // stylelint-disable-next-line declaration-no-important
    background-color: hsl(120deg, 100%, 91%) !important;
  }

  &.isClosed {
    // stylelint-disable-next-line declaration-no-important
    background-color: hsl(0deg, 100%, 93%) !important;
  }

  &.isWarning {
    // stylelint-disable-next-line declaration-no-important
    background-color: hsl(48deg, 100%, 93%) !important;
  }
}

.staticDataCell {
  @extend %data-cell;

  padding: 0.731em;
}

.month-splitter {
  border-right: 1px solid var(--bs-card-border-color);
}

tr td:last-child {
  border-right: none;
}

.content-height {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 36px;
}

.floating-row td span {
  left: 270px;
}
</style>
