<script lang="ts" setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'

import { OnClickOutside } from '@vueuse/components'
import { DateTime } from 'luxon'

import { getEachDayInMonth } from '~resources/lib/date'
import { QuotaRange, useQuotasTableRange } from '~resources/views/hotel/quotas/lib/use-range'

import DayMenu from './components/DayMenu.vue'
import EditableCell from './components/EditableCell.vue'
import HeadingCell from './components/HeadingCell.vue'
import MenuButton from './components/MenuButton.vue'

import {
  ActiveKey,
  Day,
  getActiveCellKey,
  getRoomsQuotasFromQuotas,
  RoomID,
  RoomQuota,
  RoomQuotas,
  RoomQuotaStatus,
} from './lib'
import { Quota } from './lib/mock'

const props = defineProps<{
  month: Date
  quotas: Quota[]
}>()

const monthName = computed<string>(
  () => DateTime.fromJSDate(props.month).toFormat('LLLL'),
)

const days = computed<Day[]>(() => getEachDayInMonth(props.month)
  .map((date): Day => ({
    key: date.toJSDate()
      .getTime()
      .toString(),
    date: date.toJSDate(),
    dayOfWeek: date.toFormat('EEE'),
    dayOfMonth: date.toFormat('d'),
  })))

const roomsQuotas = computed<RoomQuotas[]>(() =>
  getRoomsQuotasFromQuotas(props.quotas, days.value))

const dayCellClassNameByRoomQuotaStatus: Record<RoomQuotaStatus, string> = {
  opened: 'isOpened',
  closed: 'isClosed',
}

const dayQuotaCellClassName = (status: RoomQuota['status']) =>
  ['dayQuotaCell', status !== undefined && dayCellClassNameByRoomQuotaStatus[status]]

const quotaRange = ref<QuotaRange>(null)
const {
  setRange: setQuotaRange,
  isCellInRange: isQuotaCellInRange,
} = useQuotasTableRange({ rangeRef: quotaRange })

const releaseDaysRange = ref<QuotaRange>(null)
const {
  setRange: setReleaseDaysRange,
  isCellInRange: isReleaseDaysCellInRange,
} = useQuotasTableRange({ rangeRef: releaseDaysRange })

const activeQuotaKey = ref<ActiveKey>(null)
const activeReleaseDaysKey = ref<ActiveKey>(null)

const hoveredDay = ref<string | null>(null)

const hoveredRoomTypeID = ref<number | null>(null)

const menuRef = ref<HTMLElement | null>(null)
type MenuPosition = {
  dayKey: string
  roomTypeID: number
}
const menuPosition = ref<MenuPosition | null>(null)

const openDayMenu = (params: { trigger: HTMLElement } & MenuPosition) => {
  const {
    trigger,
    dayKey,
    roomTypeID,
  } = params
  menuRef.value = trigger
  menuPosition.value = {
    dayKey,
    roomTypeID,
  }
}

const closeDayMenu = () => {
  menuRef.value = null
  menuPosition.value = null
}

onMounted(() => {
  window.addEventListener('scroll', closeDayMenu)
})

onUnmounted(() => {
  window.removeEventListener('scroll', closeDayMenu)
})

const setHoveredRoomTypeID = (params: MenuPosition) => {
  const {
    dayKey,
    roomTypeID,
  } = params
  hoveredDay.value = dayKey
  hoveredRoomTypeID.value = roomTypeID
}

const resetHoveredRoomTypeID = () => {
  hoveredDay.value = null
  hoveredRoomTypeID.value = null
}

const formatDateToAPIDate = (date: Date): string => DateTime
  .fromJSDate(date).toFormat('yyyy-LL-dd')

const handleQuotaValue = (value: number) => {
  const range = quotaRange.value
  if (range === null) {
    console.log('quota: single value', { value })
  } else {
    type UpdateQuotasRequest = {
      room_id: RoomID
      dates: string[]
      count: number
    }
    const { roomID, quotas } = range
    const request: UpdateQuotasRequest = {
      room_id: roomID,
      dates: quotas.map(({ date }) => formatDateToAPIDate(date)),
      count: value,
    }
    console.log('quota: multiple value', { request })
  }
}

const handleReleaseDaysValue = (value: number) => {
  const range = releaseDaysRange.value
  if (range === null) {
    console.log('release days: single value', { value })
  } else {
    type UpdateReleaseDaysRequest = {
      room_id: RoomID
      dates: string[]
      release_days: number
    }
    const { roomID, quotas } = range
    const request: UpdateReleaseDaysRequest = {
      room_id: roomID,
      dates: quotas.map(({ date }) => formatDateToAPIDate(date)),
      release_days: value,
    }
    console.log('release days: multiple value', { request })
  }
}

const massEditTooltip = 'Зажмите Shift и кликните, чтобы задать значения для всех дней от выбранного до этого'
</script>
<template>
  <div class="quotasRooms">
    <div
      v-for="{
        id, label, customName, guests, count, dailyQuota,
      } in roomsQuotas"
      :key="id"
      class="card card-form"
    >
      <div class="card-body pb-0">
        <heading-cell
          :label="label"
          :custom-name="customName"
          :guests="guests"
          :count="count"
        />
        <div class="quotasTable" @scroll="closeDayMenu">
          <table>
            <thead>
              <tr>
                <th class="headingCell">
                  <div class="monthName">{{ monthName }}</div>
                </th>
                <td
                  v-for="{ key, dayOfWeek, dayOfMonth } in days"
                  :key="key"
                  class="dayCell"
                  tabindex="0"
                  @focusin="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @mouseenter="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @focusout="resetHoveredRoomTypeID"
                  @mouseleave="resetHoveredRoomTypeID"
                >
                  <div class="dayOfWeek">{{ dayOfWeek }}</div>
                  <div class="dayOfMonth"><strong>{{ dayOfMonth }}</strong></div>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr class="roomTypeHeadingRow">
                <th class="headingCell">Квоты / Продано</th>
                <td
                  v-for="{ key, quota, sold, status } in dailyQuota"
                  :key="key"
                  :class="dayQuotaCellClassName(status)"
                  :title="activeQuotaKey ? massEditTooltip : undefined"
                  tabindex="0"
                  @focusin="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @mouseenter="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @focusout="resetHoveredRoomTypeID"
                  @mouseleave="resetHoveredRoomTypeID"
                >
                  <editable-cell
                    :active-key="activeQuotaKey"
                    :cell-key="getActiveCellKey(key, id)"
                    :value="quota.toString()"
                    :max="count"
                    :in-range="isQuotaCellInRange(getActiveCellKey(key, id))"
                    @active-key="(value) => activeQuotaKey = value"
                    @range-key="(value) => setQuotaRange({
                      dailyQuota,
                      roomTypeID: id,
                      activeKey: activeQuotaKey,
                      rangeKey: value,
                    })"
                    @value="value => handleQuotaValue(value)"
                  >
                    {{ quota }} / {{ sold }}
                  </editable-cell>
                </td>
              </tr>
              <tr>
                <th class="reserveHeadingCell">(резерв)</th>
                <td
                  v-for="{ key, reserve, status } in dailyQuota"
                  :key="key"
                  class="reserveDataCell"
                  :class="dayQuotaCellClassName(status)"
                  tabindex="0"
                  @focusin="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @mouseenter="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @focusout="resetHoveredRoomTypeID"
                  @mouseleave="resetHoveredRoomTypeID"
                >
                  ({{ reserve }})
                </td>
              </tr>
              <tr>
                <th class="headingCell">релиз-дни</th>
                <td
                  v-for="{ key, releaseDays, status } in dailyQuota"
                  :key="key"
                  :class="dayQuotaCellClassName(status)"
                  :title="activeReleaseDaysKey ? massEditTooltip : undefined"
                  tabindex="0"
                  @focusin="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @mouseenter="() => {
                    setHoveredRoomTypeID({ dayKey: key, roomTypeID: id })
                  }"
                  @focusout="resetHoveredRoomTypeID"
                  @mouseleave="resetHoveredRoomTypeID"
                >
                  <editable-cell
                    :active-key="activeReleaseDaysKey"
                    :cell-key="getActiveCellKey(key, id)"
                    :value="releaseDays.toString()"
                    :max="30"
                    :in-range="isReleaseDaysCellInRange(getActiveCellKey(key, id))"
                    @active-key="(value) => activeReleaseDaysKey = value"
                    @range-key="(value) => setReleaseDaysRange({
                      dailyQuota,
                      roomTypeID: id,
                      activeKey: activeReleaseDaysKey,
                      rangeKey: value,
                    })"
                    @value="value => handleReleaseDaysValue(value)"
                  >
                    {{ releaseDays }}
                  </editable-cell>
                </td>
              </tr>
              <tr>
                <th class="headingCell" />
                <td v-for="{ key } in dailyQuota" :key="key">
                  <menu-button
                    :visible="hoveredDay === key && hoveredRoomTypeID === id"
                    @click="(element) => {
                      openDayMenu({ trigger: element, dayKey: key, roomTypeID: id })
                    }"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <OnClickOutside
      v-if="menuRef !== null && menuPosition !== null"
      @trigger="closeDayMenu"
    >
      <day-menu
        :menu-ref="menuRef"
        :menu-day-key="menuPosition.dayKey"
        @close="closeDayMenu"
        @focusin="() => setHoveredRoomTypeID(menuPosition as MenuPosition)"
        @mouseenter="() => setHoveredRoomTypeID(menuPosition as MenuPosition)"
        @focusout="resetHoveredRoomTypeID"
        @mouseleave="resetHoveredRoomTypeID"
      />
    </OnClickOutside>
  </div>
</template>
<style lang="scss" scoped>
@use '~resources/sass/variables' as vars;
@use './components/shared' as shared;

.quotasRooms {
  display: flex;
  flex-flow: column;
  gap: 2em;
}

.quotasTable {
  overflow: auto;
  font-size: 0.8em;
}

th {
  position: sticky;
  left: 0;
  background-color: vars.$body-bg;
  font-weight: normal;
  white-space: nowrap;
}

%cell {
  @include shared.cell;
}

.dayCell {
  @extend %cell;

  min-width: 4em;
  text-align: center;
}

%data-cell {
  @include shared.data-cell;
}

.dataCell {
  @extend %data-cell;
}

%reserve-cell {
  padding-block: 0.25em;
}

.reserveDataCell {
  @extend %data-cell;
  @extend %reserve-cell;
}

%heading-cell {
  @extend %cell;

  text-align: right;
  white-space: nowrap;

  &::before {
    content: '';
    position: absolute;
    top: 1px;
    right: 100%;
    bottom: 1px;
    display: block;
    width: 1px;
    background-color: vars.$body-bg;
  }
}

.headingCell {
  @extend %heading-cell;
}

.reserveHeadingCell {
  @extend %heading-cell;
  @extend %reserve-cell;
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
  &.isOpened {
    background-color: hsl(120deg, 100%, 91%);
  }

  &.isClosed {
    background-color: hsl(0deg, 100%, 93%);
  }
}
</style>
