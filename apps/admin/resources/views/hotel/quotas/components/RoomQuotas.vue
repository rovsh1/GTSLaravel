<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import { OnClickOutside } from '@vueuse/components'

import { HotelResponse } from '~api/hotel/get'
import {
  HotelRoomQuotasCountUpdateProps,
  HotelRoomQuotasUpdateProps,
  HotelRoomReleaseDaysUpdateProps,
  useHotelRoomQuotasUpdate,
} from '~api/hotel/quotas/update'
import { HotelRoom } from '~api/hotel/room'

import OverlayLoading from '~components/OverlayLoading.vue'

import DayMenu from './DayMenu/DayMenu.vue'
import EditableCell from './EditableCell.vue'
import RoomHeader from './RoomHeader.vue'

import { MenuParams, useDayMenu } from './DayMenu/use-day-menu'
import { Day, Month, RoomQuota, RoomQuotaStatus } from './lib'
import { useTableRange } from './lib/use-range'

const props = defineProps<{
  hotel: HotelResponse
  room: HotelRoom
  days: Day[]
  months: Month[]
  allQuotas: Map<string, RoomQuota>
  editable: boolean
  reloadActiveRoom: boolean
  openingDayMenuRoomId: number | null
}>()

const emit = defineEmits<{
  (event: 'update', roomID: number): void
  (event: 'open-day-menu-in-another-room', roomID: number | null): void
}>()

const isOpeningAnotherRoomDayMenu = ref<boolean>(false)

watch(() => props.openingDayMenuRoomId, () => {
  isOpeningAnotherRoomDayMenu.value = (!((props.openingDayMenuRoomId === null || props.openingDayMenuRoomId === props.room.id)))
})

const dayMenuElementRef = ref<HTMLElement | null>(null)
const dayMenuActionCompleted = ref<boolean>(false)

const editedQuotasCount = ref<number | null>(null)
const activeQuotasCountKey = ref<string | null>(null)

const editedReleaseDays = ref<number | null>(null)
const activeReleaseDaysKey = ref<string | null>(null)

const activeCellType = ref<string | null>(null)

const months = computed<Month[]>(() => props.months)

const allQuotas = computed<Map<string, RoomQuota>>(() => props.allQuotas)

const daysLocal = computed<Day[]>(() => props.days)

const {
  rangeRef: quotasRange,
  isCellInRange: inQuotasRange,
  addItemsToRange: addRangeToRangeQuotas,
  addItemToRange: addPickToRangeQuotas,
} = useTableRange({
  days: daysLocal,
  activeKey: activeQuotasCountKey,
  roomID: props.room.id,
})

const {
  rangeRef: releaseDaysRange,
  isCellInRange: inReleaseDaysRange,
  addItemsToRange: addRangeToRangeReleaseDays,
  addItemToRange: addPickToRangeReleaseDays,
} = useTableRange({
  days: daysLocal,
  activeKey: activeReleaseDaysKey,
  roomID: props.room.id,
})

const dayCellClassNameByRoomQuotaStatus: Record<RoomQuotaStatus, string> = {
  opened: 'isOpened',
  closed: 'isClosed',
  warning: 'isWarning',
}

const dayQuotaCellClassName = (status: RoomQuota['status']) =>
  ['dayQuotaCell', status !== null && dayCellClassNameByRoomQuotaStatus[status]]

const {
  dayMenuRef,
  dayMenuPosition,
  openDayMenu,
  closeDayMenu,
} = useDayMenu()

const getDatesFromRange = (range: string[]): string[] => {
  if (range && range.length) {
    const getDaysKeysFromRange = range.map((dayInRange: string) => dayInRange.split('-')[0])
    const datesFromLocalsDays = daysLocal.value.filter(
      (obj) => getDaysKeysFromRange.includes(obj.key),
    ).map((day) => day.date)
    return datesFromLocalsDays
  }
  return []
}

const dayMenuDates = computed<string[] | null>(() => {
  const fromQuotasRange = getDatesFromRange(quotasRange.value)
  const fromReleaseDaysRange = getDatesFromRange(releaseDaysRange.value)
  const fromMenuPosition = dayMenuPosition.value !== null
    ? daysLocal.value.filter((obj) => obj.key === dayMenuPosition.value?.dayKey).map((obj) => obj.date)
    : null
  if (fromQuotasRange.length) return fromQuotasRange
  if (fromReleaseDaysRange.length) return fromReleaseDaysRange
  return fromMenuPosition
})

const dayMenuDone = () => {
  closeDayMenu()
  dayMenuActionCompleted.value = true
  emit('update', props.room.id)
}

type HandleValue<R> = (date: string, value: number) => R

const hotelRoomQuotasUpdateProps = ref<HotelRoomQuotasUpdateProps | null>(null)

const {
  execute: executeHotelRoomQuotasUpdate,
  data: hotelRoomQuotasUpdateData,
  isFetching: isHotelRoomQuotasUpdateFetching,
} = useHotelRoomQuotasUpdate(hotelRoomQuotasUpdateProps)

watch(hotelRoomQuotasUpdateData, (value) => {
  if (value === null || !value.success) return
  hotelRoomQuotasUpdateProps.value = null
  emit('update', props.room.id)
  activeQuotasCountKey.value = null
  activeReleaseDaysKey.value = null
})

type GetQuotasCountPayload = HandleValue<HotelRoomQuotasCountUpdateProps>
const getQuotasCountPayload: GetQuotasCountPayload = (dateKey, value) => {
  const common = {
    kind: 'count' as const,
    hotelID: props.hotel.id,
    roomID: props.room.id,
  }
  const range = quotasRange.value
  if (range && range.length) {
    return {
      ...common,
      dates: getDatesFromRange(range),
      count: value,
    }
  }
  return {
    ...common,
    dates: daysLocal.value.filter((obj) => obj.key === dateKey).map((obj) => obj.date),
    count: value,
  }
}

const handleQuotaValue: HandleValue<void> = (dateKey, value) => {
  hotelRoomQuotasUpdateProps.value = getQuotasCountPayload(dateKey, value)
  executeHotelRoomQuotasUpdate()
}

type GetReleaseDaysPayload = HandleValue<HotelRoomReleaseDaysUpdateProps>
const getReleaseDaysPayload: GetReleaseDaysPayload = (dateKey, value) => {
  const common = {
    kind: 'releaseDays' as const,
    hotelID: props.hotel.id,
    roomID: props.room.id,
  }
  const range = releaseDaysRange.value
  if (range && range.length) {
    return {
      ...common,
      dates: getDatesFromRange(range),
      releaseDays: value,
    }
  }
  return {
    ...common,
    dates: daysLocal.value.filter((obj) => obj.key === dateKey).map((obj) => obj.date),
    releaseDays: value,
  }
}

const handleReleaseDaysValue: HandleValue<void> = (dateKey, value) => {
  hotelRoomQuotasUpdateProps.value = getReleaseDaysPayload(dateKey, value)
  executeHotelRoomQuotasUpdate()
}

const getCurrentCellKey = (dayKey: string) => `${dayKey}-${props.room.id}`

const getDayDataByPropertyName = (key: string, property: keyof RoomQuota): any => {
  const genKey = getCurrentCellKey(key)
  let value: RoomQuota | undefined | null = null
  if (allQuotas.value.has(genKey)) {
    value = allQuotas.value.get(genKey)
    if (value) {
      return value[property] !== null && value[property] !== undefined ? value[property] : null
    }
  }
  return null
}

const updateQuotas = (key: string, roomID: number, value: number | null) => {
  if (value === null) return
  handleQuotaValue(key, value)
}

const updateReleaseDays = (key: string, roomID: number, value: number | null) => {
  if (value === null) return
  handleReleaseDaysValue(key, value)
}

const resetActiveKey = () => {
  activeQuotasCountKey.value = null
  editedQuotasCount.value = null
  quotasRange.value = []
  releaseDaysRange.value = []
  dayMenuActionCompleted.value = false
}
</script>
<template>
  <div>
    <div class="roomHeader">
      <room-header :label="room.name" :guests="room.guestsCount" :count="room.roomsNumber" />
    </div>
    <div class="quotasTables">
      <OverlayLoading v-if="isHotelRoomQuotasUpdateFetching || reloadActiveRoom" />
      <div class="quotasTable card" @scroll="closeDayMenu">
        <table class="card-body">
          <thead>
            <tr class="floating-row">
              <th class="headingCell">
                Месяц
              </th>
              <td
                v-for="{ monthKey, daysCount, monthName } in months"
                :key="getCurrentCellKey(monthKey)"
                :colspan="daysCount"
                :class="[months.length > 1 ? 'month-splitter' : '']"
              >
                <div class="monthName">
                  <span><b>{{ monthName }}</b></span>
                </div>
              </td>
            </tr>
            <tr>
              <th class="headingCell">
                День / Число
              </th>
              <template
                v-for="{ key, isHoliday, dayOfWeek, dayOfMonth, isLastDayInMonth } in daysLocal"
                :key="getCurrentCellKey(key)"
              >
                <td class="dayCell" :class="{ isHoliday: isHoliday, 'month-splitter': isLastDayInMonth }" tabindex="0">
                  <div class="dayOfWeek">{{ dayOfWeek }}</div>
                  <div class="dayOfMonth"><strong>{{ dayOfMonth }}</strong></div>
                </td>
              </template>
            </tr>
          </thead>
          <tbody>
            <tr class="roomTypeHeadingRow">
              <th class="headingCell">
                <div class="content-height d-flex justify-content-end align-items-center">
                  Квоты
                </div>
                <div class="content-height d-flex justify-content-end align-items-center">
                  Релиз-дни
                </div>
                <div class="content-height d-flex justify-content-end align-items-center">
                  Продано / Резерв
                </div>
              </th>
              <template v-for="{ key, isLastDayInMonth } in daysLocal" :key="key">
                <td
                  :class="[dayQuotaCellClassName(
                    (getDayDataByPropertyName(key, 'status') === 'opened'
                      && getDayDataByPropertyName(key, 'quota') === 0
                      ? 'warning' : getDayDataByPropertyName(key, 'status'))), isLastDayInMonth ? 'month-splitter' : '']"
                  tabindex="-1"
                >
                  <div tabindex="-1" class="content-height">
                    <EditableCell
                      :day-menu-ref="dayMenuElementRef"
                      :day-menu-action-completed="dayMenuActionCompleted || isOpeningAnotherRoomDayMenu"
                      :init-value="getDayDataByPropertyName(key, 'quota')"
                      :value="editedQuotasCount !== null && inQuotasRange(getCurrentCellKey(key))
                        ? editedQuotasCount : (activeQuotasCountKey === getCurrentCellKey(key) ? editedQuotasCount : getDayDataByPropertyName(key, 'quota'))
                      "
                      :min="0"
                      :max="room.roomsNumber"
                      :range-error="(min, max) => `Есть только ${max} таких номеров`"
                      cell-type="quota"
                      :active-cell-type="activeCellType"
                      :cell-key="getCurrentCellKey(key)"
                      :active-cell-key="activeQuotasCountKey"
                      :room-i-d="room.id"
                      :editable="editable"
                      :in-range="inQuotasRange(getCurrentCellKey(key))"
                      :range-exist="quotasRange && quotasRange.length > 0"
                      @change="value => updateQuotas(key, room.id, value)"
                      @input="value => editedQuotasCount = value"
                      @pick-key="value => addPickToRangeQuotas(value)"
                      @range-key="value => addRangeToRangeQuotas(value)"
                      @active-key="(value, type) => {
                        activeQuotasCountKey = value
                        activeCellType = type
                        activeReleaseDaysKey = null
                        editedQuotasCount = getDayDataByPropertyName(key, 'quota')
                      }"
                      @context-menu="(element) => {
                        openDayMenu({
                          trigger: element, dayKey: key, roomTypeID: room.id,
                        } as MenuParams)
                        emit('open-day-menu-in-another-room', room.id)
                      }"
                      @reset="resetActiveKey"
                    />
                  </div>
                  <div class="content-height">
                    <EditableCell
                      :day-menu-ref="dayMenuElementRef"
                      :day-menu-action-completed="dayMenuActionCompleted || isOpeningAnotherRoomDayMenu"
                      :value="editedReleaseDays !== null && inReleaseDaysRange(getCurrentCellKey(key))
                        ? editedReleaseDays : (activeReleaseDaysKey === getCurrentCellKey(key) ? editedReleaseDays : getDayDataByPropertyName(key, 'releaseDays'))
                      "
                      :init-value="getDayDataByPropertyName(key, 'releaseDays')"
                      :min="0"
                      :max="99"
                      cell-type="releaseDay"
                      :active-cell-type="activeCellType"
                      :cell-key="getCurrentCellKey(key)"
                      :active-cell-key="activeReleaseDaysKey"
                      :room-i-d="room.id"
                      :editable="editable"
                      :in-range="inReleaseDaysRange(getCurrentCellKey(key))"
                      :range-exist="releaseDaysRange && releaseDaysRange.length > 0"
                      @change="value => updateReleaseDays(key, room.id, value)"
                      @input="value => editedReleaseDays = value"
                      @pick-key="value => addPickToRangeReleaseDays(value)"
                      @range-key="value => addRangeToRangeReleaseDays(value)"
                      @active-key="(value, type) => {
                        activeQuotasCountKey = null
                        activeCellType = type
                        activeReleaseDaysKey = value
                        editedReleaseDays = getDayDataByPropertyName(key, 'releaseDays')
                      }"
                      @context-menu="(element) => {
                        openDayMenu({
                          trigger: element, dayKey: key, roomTypeID: room.id,
                        } as MenuParams)
                        emit('open-day-menu-in-another-room', room.id)
                      }"
                      @reset="resetActiveKey"
                    />
                  </div>
                  <div class="content-height">
                    <template
                      v-if="getDayDataByPropertyName(key, 'sold') === null && getDayDataByPropertyName(key, 'reserve') === null"
                    >
                      &nbsp;
                    </template>
                    <template v-else-if="getDayDataByPropertyName(key, 'sold') === null">
                      0 / {{ getDayDataByPropertyName(key, 'reserve') }}
                    </template>
                    <template v-else-if="getDayDataByPropertyName(key, 'reserve') === null">
                      {{ getDayDataByPropertyName(key, 'sold') }} / 0
                    </template>
                    <template v-else>
                      {{ getDayDataByPropertyName(key, 'sold') }} /
                      {{ getDayDataByPropertyName(key, 'reserve') }}
                    </template>
                  </div>
                </td>
              </template>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <Teleport v-if="editable && dayMenuRef !== null && dayMenuPosition !== null" to="#hotel-quotas-wrapper">
      <OnClickOutside
        @trigger="() => {
          closeDayMenu()
          emit('open-day-menu-in-another-room', null)
        }"
      >
        <day-menu
          :menu-ref="dayMenuRef"
          :menu-day-key="dayMenuPosition ? dayMenuPosition.dayKey : null"
          :hotel="hotel.id"
          :room="room.id"
          :dates="dayMenuDates"
          @done="dayMenuDone"
          @set-menu-element="value => dayMenuElementRef = value"
        />
      </OnClickOutside>
    </Teleport>
  </div>
</template>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;
@use 'shared' as shared;

.roomHeader {
  padding-left: bs.$form-select-padding-x;
}

.quotasTables {
  position: relative;
  display: flex;
  flex-flow: column;
  gap: 1em;
}

.quotasTable {
  --cell-width: 4em;

  overflow: auto;
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

%data-cell {
  @include shared.data-cell;
}

.dataCell {
  @extend %data-cell;
}

%heading-cell {
  @extend %cell;

  padding-left: 1.5em;
  text-align: right;
  white-space: nowrap;
}

.headingCell {
  @extend %heading-cell;

  z-index: 1;
  width: 142px;
  max-width: 142px;
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

.roomTypeHeadingRow {
  th,
  td {
    border-top: 1px solid lightgray;
  }
}

.roomTypeHeadingCell {
  padding-right: 1em;
}

.dayQuotaCell {
  max-width: var(--cell-width);

  &.isOpened {
    background-color: hsl(120deg, 100%, 91%);
  }

  &.isClosed {
    background-color: hsl(0deg, 100%, 93%);
  }

  &.isWarning {
    background-color: hsl(48deg, 100%, 93%);
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
  left: 142px;
}
</style>
