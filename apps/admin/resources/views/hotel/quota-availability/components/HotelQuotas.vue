<script lang="ts" setup>
import { computed } from 'vue'

import { DateTime } from 'luxon'

import { Day, Month, RoomQuotaStatus } from '~resources/views/hotel/quotas/components/lib'
import { HotelInfo, QuotaAvailability, QuotaInfo } from '~resources/vue/api/hotel/quotas/availability'

type HotelQuotasAccumulationData = {
  hotel: HotelInfo
  quotasMap: Map<string, QuotaInfo>
}

const props = withDefaults(defineProps<{
  hotelsQuotas: QuotaAvailability[] | null
  days: Day[]
  months: Month[]
}>(), {

})

const monthsLocal = computed<Month[]>(() => props.months)

const daysLocal = computed<Day[]>(() => props.days)

const hotelsQuotasLocal = computed<HotelQuotasAccumulationData[]>(() => {
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
      quotasMap: hotelQuotasMap,
    })
  })
  return hotelsQuotasAccumulation
})

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

</script>

<template>
  <div class="quotasTables">
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
          <tr
            v-for="hotelQuota in hotelsQuotasLocal"
            :key="hotelQuota.hotel.id"
            class="typeHeadingRow"
            @click="() => {
              console.log('tyt')
            }"
          >
            <th class="headingCell">
              {{ hotelQuota.hotel.name }}
            </th>
            <td class="otherCell">квоты</td>
            <template v-for="{ key, isLastDayInMonth } in daysLocal" :key="key">
              <td
                class="quotaCell"
                :class="[isLastDayInMonth ? 'month-splitter' : '', dayQuotaCellClassName(getHotelDayQuotaStatus(
                  getHotelDayDataByPropertyName(`${key}-${hotelQuota.hotel.id}`, hotelQuota.quotasMap, 'countAvailable')))]"
              >
                {{ getHotelDayDataByPropertyName(`${key}-${hotelQuota.hotel.id}`, hotelQuota.quotasMap, 'countAvailable') }}
              </td>
            </template>
          </tr>
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
  --cell-width: 4em;

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
  min-width: 200px;
  max-width: 200px;
  white-space: normal;
}

.otherCell {
  width: 70px;
  max-width: 70px;
  padding: 0 0.5rem;
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
  left: 200px;
}
</style>
