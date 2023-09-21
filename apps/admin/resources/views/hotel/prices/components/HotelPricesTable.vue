<script lang="ts" setup>
import { nextTick, ref, watch, watchEffect } from 'vue'

import { useToggle } from '@vueuse/core'
import { nanoid } from 'nanoid'

import { updateRoomSeasonPrice } from '~resources/api/hotel/prices/seasons'
import { formatSeasonPeriod } from '~resources/lib/date'
import { generateHashFromObject } from '~resources/lib/hash'

import BaseDialog from '~components/BaseDialog.vue'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import OverlayLoading from '~components/OverlayLoading.vue'

import EditableCell from './EditableCell.vue'
import SeasonEditPrice from './SeasonEditPrice.vue'
import SeasonPriceDaysTable from './SeasonPriceDaysTable.vue'

import { getStatusClassByFlag } from '../lib/status'
import { PricesAccumulationData, Room, RoomSeasonPrice, Season, SeasonPeriod } from '../lib/types'

const props = withDefaults(defineProps<{
  hotelId: number
  roomData: Room
  seasonsData: Season[]
  pricesData: RoomSeasonPrice[] | null
  isFetching: boolean
  closeAllBut?: string
}>(), {
  closeAllBut: undefined,
})

const emit = defineEmits<{
  (event: 'updateData'): void
  (event: 'closeAll', item: string | undefined): void
}>()

const [isOpened, toggleModal] = useToggle()
const tableElementID = `price-table-${nanoid()}`
const baseColumnsWidth = ref<HTMLElement | null>(null)
const baseRowWidth = ref<HTMLElement | null>(null)

const roomSeasonsPricesData = ref<PricesAccumulationData[]>([])

const isEditSeasonData = ref<boolean>(false)
const waitComponentProcess = ref<boolean>(false)

const currentSeasonPeriod = ref<SeasonPeriod | null>(null)
const currentSeasonData = ref<PricesAccumulationData | null>(null)
const currentCellID = ref<string | undefined>()

const editableSeasonData = ref<PricesAccumulationData | null>(null)
const editableSeasonNewPrice = ref<number | null>(null)

const setIdenticalColumnsWidth = (columnCount: number) => {
  const rowWidth = baseRowWidth.value?.offsetWidth
  const columnWidth = baseColumnsWidth.value?.offsetWidth
  const containerWidth = rowWidth && columnWidth ? rowWidth - columnWidth : null
  if (!containerWidth) return
  const calculateColumnWidth = containerWidth / columnCount
  const columns = document.querySelectorAll(`#${tableElementID} .priced`)
  columns.forEach((column) => {
    const columnElement = column as HTMLElement
    columnElement.style.width = `${calculateColumnWidth}px`
  })
}

watch(() => props.closeAllBut, (value) => {
  if (value !== currentCellID.value) {
    isEditSeasonData.value = false
    currentSeasonData.value = null
  }
})

const setPricesAccumulationData = (obj: PricesAccumulationData): PricesAccumulationData => {
  const hashID = generateHashFromObject({
    hotelID: obj.hotelID,
    roomID: obj.roomID,
    seasonID: obj.seasonID,
    isResident: obj.isResident,
  })
  return { id: hashID, ...obj }
}

watchEffect(() => {
  if (!props.isFetching) {
    roomSeasonsPricesData.value = []
    props.roomData.price_rates.forEach((rate) => {
      props.seasonsData.forEach((season) => {
        for (let i = 1; i <= props.roomData.guests_count; i++) {
          const accumulationDataObject: PricesAccumulationData = {
            hotelID: props.hotelId,
            roomID: props.roomData.id,
            seasonID: season.id,
            seasonStatusFlag: null,
            guestsCount: i,
            rateID: rate.id,
            rateName: rate.name,
            price: null,
          }
          roomSeasonsPricesData.value.push(setPricesAccumulationData({ ...accumulationDataObject, isResident: true }))
          roomSeasonsPricesData.value.push(setPricesAccumulationData({ ...accumulationDataObject, isResident: false }))
        }
      })
    })
    props.pricesData?.forEach((seasonPrice) => {
      roomSeasonsPricesData.value.forEach((roomSeasonPrice) => {
        const sourceRoomSeasonPrice = roomSeasonPrice
        if (seasonPrice.room_id === roomSeasonPrice.roomID
          && seasonPrice.season_id === roomSeasonPrice.seasonID
          && seasonPrice.rate_id === roomSeasonPrice.rateID
          && seasonPrice.is_resident === roomSeasonPrice.isResident
          && seasonPrice.guests_count === roomSeasonPrice.guestsCount) {
          sourceRoomSeasonPrice.price = seasonPrice.price
          sourceRoomSeasonPrice.seasonStatusFlag = seasonPrice.has_date_prices
        }
      })
    })
    nextTick(() => {
      const columnsInRow = props.seasonsData.length * props.roomData.guests_count
      setIdenticalColumnsWidth(columnsInRow)
    })
  }
})

const editableSeasonDays = (item: PricesAccumulationData) => {
  const seasonFiltered = props.seasonsData.filter((season) => season.id === item.seasonID)
  currentSeasonPeriod.value = seasonFiltered && seasonFiltered.length > 0 ? {
    from: seasonFiltered[0].date_start,
    to: seasonFiltered[0].date_end,
  } : null
  if (!currentSeasonPeriod.value) return
  if (currentSeasonData.value?.id === item.id) {
    isEditSeasonData.value = false
    currentSeasonData.value = null
  } else {
    isEditSeasonData.value = true
    currentSeasonData.value = item
  }
  currentCellID.value = item.id
  emit('closeAll', currentCellID.value)
}

const changeSeasonPrice = async (item: PricesAccumulationData, newPrice: number | null) => {
  editableSeasonData.value = item
  editableSeasonNewPrice.value = newPrice
  toggleModal()
}

const onSubmitChangeData = async () => {
  if (!editableSeasonData.value) return
  const { data: updateStatusResponse } = await updateRoomSeasonPrice({
    ...editableSeasonData.value,
    price: editableSeasonNewPrice.value,
  })
  if (updateStatusResponse.value?.success) {
    if (currentSeasonData.value && currentSeasonData.value.id === editableSeasonData.value.id) {
      currentSeasonData.value.price = editableSeasonNewPrice.value
    }
    if (editableSeasonNewPrice.value === null && currentSeasonData.value?.id === editableSeasonData.value.id) {
      currentSeasonData.value = null
      currentSeasonPeriod.value = null
      editableSeasonNewPrice.value = null
      isEditSeasonData.value = false
    }
    emit('updateData')
  } else {
    showToast({ title: 'Не удалось изменить цену' })
  }
  toggleModal(false)
}

const handlerUpdateSeasonDaysData = (status :boolean) => {
  if (!status) {
    emit('updateData')
    waitComponentProcess.value = false
  } else {
    waitComponentProcess.value = true
  }
}
</script>

<template>
  <div class="hotel-prices-table-wrapper">
    <OverlayLoading v-if="isFetching || waitComponentProcess" />
    <div class="table-wrapper-overflow">
      <table :id="tableElementID" ref="containerElement" class="hotel-prices table table-bordered table-sm table-light">
        <caption>Стоимость</caption>
        <thead>
          <tr ref="baseRowWidth">
            <th ref="baseColumnsWidth" class="text-center align-middle" :colspan="2">Сезон</th>
            <th
              v-for="season in seasonsData"
              :key="season.id"
              class="text-center align-middle"
              :colspan="roomData.guests_count"
            >
              <div class="name">{{ season.name }}</div>
              <div class="period">
                {{ formatSeasonPeriod({ date_start: season.date_start, date_end: season.date_end }) }}
              </div>
            </th>
          </tr>
          <tr>
            <th class="text-center align-middle" colspan="2">Количество гостей</th>
            <template v-for="season in seasonsData" :key="season.id">
              <th v-for="count in roomData.guests_count" :key="count" class="text-center align-middle">{{ count }}</th>
            </template>
          </tr>
        </thead>
        <tbody>
          <template v-for="rate in roomData.price_rates" :key="rate.id">
            <tr>
              <th ref="susctractElement1" rowspan="2" class="rate-name text-left align-middle">
                {{ rate.name }}
              </th>
              <th ref="susctractElement2" class="type-name text-left align-middle">Резидент</th>
              <template v-for="item in roomSeasonsPricesData" :key="item.id">
                <td
                  v-if="item.rateID == rate.id && item.isResident"
                  :class="[getStatusClassByFlag(item.seasonStatusFlag)]"
                  class="priced align-middle"
                >
                  <EditableCell
                    :value="item.price"
                    :enable-context-menu="!!item.price"
                    @activated-context-menu="editableSeasonDays(item)"
                    @change="(value: any) => changeSeasonPrice(item, value)"
                  />
                </td>
              </template>
            </tr>
            <tr>
              <th class="type-name text-left align-middle">Не резидент</th>
              <template v-for="item in roomSeasonsPricesData" :key="item.id">
                <td
                  v-if="item.rateID == rate.id && !item.isResident"
                  :class="[getStatusClassByFlag(item.seasonStatusFlag)]"
                  class="priced align-middle"
                >
                  <EditableCell
                    :value="item.price"
                    :enable-context-menu="!!item.price"
                    @activated-context-menu="editableSeasonDays(item)"
                    @change="(value: any) => changeSeasonPrice(item, value)"
                  />
                </td>
              </template>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <SeasonEditPrice
      v-if="isEditSeasonData && currentSeasonPeriod && currentSeasonData"
      :season-period="currentSeasonPeriod"
      :season-data="currentSeasonData"
      :cell-id="currentCellID"
      @update-season-days-data="handlerUpdateSeasonDaysData"
    />
    <SeasonPriceDaysTable
      v-if="isEditSeasonData && currentSeasonPeriod && currentSeasonData"
      :season-period="currentSeasonPeriod"
      :season-data="currentSeasonData"
      :refresh-days-prices="isFetching"
      @update-season-days-data="handlerUpdateSeasonDaysData"
    />
    <BaseDialog :opened="isOpened as boolean" @close="toggleModal(false)">
      <template #title>Вы уверены что хотите перезаписать цену на весь сезон?</template>
      <template #actions-end>
        <button class="btn btn-primary" type="button" @click="onSubmitChangeData">
          ОК
        </button>
        <button class="btn btn-cancel" type="button" @click="toggleModal(false)">Отмена</button>
      </template>
    </BaseDialog>
  </div>
</template>

<style lang="scss" scoped>
.hotel-prices-table-wrapper {
  position: relative;
}

.priced {
  height: 32px;
}
</style>
