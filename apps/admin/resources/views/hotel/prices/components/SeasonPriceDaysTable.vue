<script lang="ts" setup>
import { computed, nextTick, onMounted, ref, watch } from 'vue'

import { formatDateTimeToAPIDate, formatSeasonPeriod, parseAPIDate } from 'gts-common/helpers/date'
import OverlayLoading from 'gts-components/Base/OverlayLoading'
import { showToast } from 'gts-components/Bootstrap/BootstrapToast/index'
import { Month } from 'gts-quotas-component/types'
import { DateTime } from 'luxon'
import { nanoid } from 'nanoid'

import { daysOfWeek } from '~resources/js/config/constants'

import { updateRoomSeasonPricesByDay, useRoomSeasonsDaysPricesListAPI } from '~api/hotel/prices/seasons'

import EditableCell from './EditableCell.vue'

import { getStatusClassByFlag, getStatusClassByPrice } from '../lib/status'
import { PricesAccumulationData, PricesAccumulationDataForDays, SeasonPeriod } from '../lib/types'

const props = withDefaults(defineProps<{
  seasonData: PricesAccumulationData
  seasonPeriod: SeasonPeriod
  refreshDaysPrices?: boolean
  traveLineIntegrationEnabled?: boolean
}>(), {
  traveLineIntegrationEnabled: false,
})

const emit = defineEmits<{
  (event: 'updateSeasonDaysData', status: boolean): void
}>()

const tableElementid = `price-table-${nanoid()}`
const baseColumnHeight = ref<HTMLElement | null>(null)

const daysInPeriod = ref<PricesAccumulationDataForDays[]>([])

const periodMonths = computed<Month[]>(() => {
  const monthsMap = new Map<string, number>()
  daysInPeriod.value.forEach((day) => {
    if (!day.date) return
    const date = DateTime.fromISO(day.date)
    const monthKey = date.toFormat('yyyy-MM')
    monthsMap.set(monthKey, (monthsMap.get(monthKey) || 0) + 1)
  })
  const result = Array.from(monthsMap).map(([monthKey, daysCount]) => {
    const monthName = DateTime.fromFormat(monthKey, 'yyyy-MM')
    return {
      monthKey,
      monthName: monthName.toFormat('LLLL yyyy'),
      daysCount,
    }
  })
  return result
})

const seasonData = computed<PricesAccumulationData>(() => props.seasonData)

const seasonPeriod = computed<SeasonPeriod>(() => props.seasonPeriod)

const daysOfWeekShortsName: string[] = daysOfWeek.map((day) => day.shortName)

const { data: prices, isFetching: pricesLoad, execute: fetchPrices } = useRoomSeasonsDaysPricesListAPI(seasonData)

const setIdenticalColumnsSize = () => {
  const columns = document.querySelectorAll(`#${tableElementid} .priced`)
  columns.forEach((column) => {
    const columnElement = column as HTMLElement
    columnElement.style.height = '100px !important'
    columnElement.style.width = '55px !important'
  })
}

const getWeekDaysInPeriod = (startDate: string, endDate: string): PricesAccumulationDataForDays[] => {
  const pricesDays: PricesAccumulationDataForDays[] = []
  let currentDay = parseAPIDate(startDate)
  const endDay = parseAPIDate(endDate)
  while (currentDay <= endDay) {
    const dayOfWeekIndex = currentDay.weekday - 1
    const priceDay = {
      ...seasonData.value,
      date: formatDateTimeToAPIDate(currentDay),
      dayOfWeek: daysOfWeekShortsName[dayOfWeekIndex],
      dayNumberTitle: currentDay.day.toString().padStart(2, '0'),
    }
    priceDay.price = null
    priceDay.id = nanoid()
    pricesDays.push(priceDay)
    currentDay = currentDay.plus({ days: 1 })
  }
  return pricesDays
}

const filledPricesDays = async () => {
  await fetchPrices()
  const daysInPeriodFilled = getWeekDaysInPeriod(seasonPeriod.value.from, seasonPeriod.value.to)
  if (prices.value) {
    const pricesForCurrentRoom = prices.value.filter((price) => price.guests_count === seasonData.value.guestsCount
    && price.is_resident === seasonData.value.isResident
    && price.rate_id === seasonData.value.rateID
    && price.room_id === seasonData.value.roomID
    && price.season_id === seasonData.value.seasonID)
    if (pricesForCurrentRoom.length) {
      const priceMap = new Map()
      pricesForCurrentRoom.forEach((priceData) => {
        const date = priceData.date ? formatDateTimeToAPIDate(parseAPIDate(priceData.date)) : null
        if (date) {
          priceMap.set(date, priceData.price)
        }
      })
      daysInPeriodFilled.forEach((dayInPeriod) => {
        const { date } = dayInPeriod
        const dayInPeriodSource = dayInPeriod
        if (date && priceMap.has(date)) {
          dayInPeriodSource.price = priceMap.get(date)
        }
      })
    }
  }
  daysInPeriod.value = daysInPeriodFilled
  nextTick(() => {
    setIdenticalColumnsSize()
  })
}

watch(() => props.seasonData, async () => {
  await filledPricesDays()
})

watch(() => props.refreshDaysPrices, async () => {
  if (!props.refreshDaysPrices) {
    await filledPricesDays()
  }
})

const changeData = async (item: PricesAccumulationDataForDays, newPrice: number | null) => {
  if (newPrice !== null) {
    emit('updateSeasonDaysData', true)
    const { data: updateStatusResponse } = await updateRoomSeasonPricesByDay({
      ...seasonData.value,
      price: newPrice,
      date: item.date as string,
    })
    if (!updateStatusResponse.value || !updateStatusResponse.value.success) {
      showToast({ title: 'Не удалось изменить цену' })
    }
    emit('updateSeasonDaysData', false)
  }
}

onMounted(async () => {
  await filledPricesDays()
})
</script>

<template>
  <div class="caption">{{ formatSeasonPeriod({ date_start: seasonPeriod.from, date_end: seasonPeriod.to }) }}</div>
  <div class="season-price-days-table">
    <OverlayLoading v-if="pricesLoad" />
    <div class="table-wrapper">
      <table :id="'id'" class="hotel-prices table table-bordered table-sm table-light">
        <thead>
          <tr class="month-title">
            <th class="text-center align-middle" colspan="2">Месяц</th>
          </tr>
          <tr>
            <th class="text-center align-middle" colspan="2">Дни недели</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th ref="baseColumnHeight" class="rate-name text-left align-middle">{{ seasonData.rateName }}</th>
            <th class="type-name text-left align-middle">{{ seasonData.isResident ? 'Резидент' : 'Не резидент' }}</th>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="table-wrapper">
      <table :id="tableElementid" class="hotel-prices table table-bordered table-sm table-light">
        <thead>
          <tr class="month-title floating-row">
            <th
              v-for="{ monthKey, daysCount, monthName } in periodMonths"
              :key="monthKey"
              :colspan="daysCount"
              class="align-middle month-title-padding"
            >
              <span>{{ monthName }}</span>
            </th>
          </tr>
          <tr>
            <th
              v-for="item in daysInPeriod"
              :key="item.id"
              class="align-middle text-center"
            >
              {{ item.dayOfWeek }} <br> {{ item.dayNumberTitle }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td
              v-for="item in daysInPeriod"
              :key="item.id"
              :class="[traveLineIntegrationEnabled
                ? getStatusClassByFlag(true) : getStatusClassByPrice(seasonData.price, item.price)]"
              class="priced align-middle text-center"
            >
              <EditableCell
                :value="item.price"
                :value-place-holder="seasonData.price"
                :enable-context-menu="false"
                @change="value => changeData(item, value)"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style lang="scss">
.table-wrapper {
  padding: 0.313rem 0;

  &:first-child {
    table {
      width: 12.5rem;
      min-width: 12.5rem;
    }
  }

  &:last-child {
    overflow-x: scroll;
  }
}

.season-price-days-table {
  position: relative;
  display: flex;
}

.priced {
  min-width: 3.438rem;
  padding: 0 0.313rem;
}

.season-price-days-table table tr.month-title {
  height: auto;

  th,
  td {
    text-align: left;
  }

  .month-title-padding {
    padding: 0.25rem 0;
  }
}
</style>
