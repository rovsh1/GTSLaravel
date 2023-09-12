<script lang="ts" setup>
import { computed, nextTick, onMounted, ref, watch } from 'vue'

import { nanoid } from 'nanoid'

import { updateRoomSeasonPricesByDay, useRoomSeasonsDaysPricesListAPI } from '~resources/api/hotel/prices/seasons'
import { formatDateTimeToAPIDate, formatSeasonPeriod, parseAPIDate } from '~resources/lib/date'

import { showToast } from '~components/Bootstrap/BootstrapToast'
import OverlayLoading from '~components/OverlayLoading.vue'

import EditableCell from './EditableCell.vue'

import { daysOfWeek } from '../lib/constants'
import { getStatusClassByPrice } from '../lib/status'
import { PricesAccumulationData, PricesAccumulationDataForDays, SeasonPeriod } from '../lib/types'

const props = withDefaults(defineProps<{
  seasonData: PricesAccumulationData
  seasonPeriod: SeasonPeriod
  refreshDaysPrices?: boolean
}>(), {

})

const emit = defineEmits<{
  (event: 'updateSeasonDaysData', status: boolean): void
}>()

const tableElementid = `price-table-${nanoid()}`
const baseColumnHeight = ref<HTMLElement | null>(null)

const daysInPeriod = ref<PricesAccumulationDataForDays[]>([])

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
  if (newPrice) {
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
              :class="[getStatusClassByPrice(seasonData.price, item.price)]"
              class="priced align-middle text-center"
            >
              <EditableCell :value="item.price" :enable-context-menu="false" @change="value => changeData(item, value)" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style lang="scss">
.table-wrapper {
  padding: 5px 0;

  &:first-child {
    table {
      width: 200px;
      min-width: 200px;
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
  min-width: 55px;
  padding: 0 5px;
}
</style>
