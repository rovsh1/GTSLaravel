<script lang="ts" setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'

import { OnClickOutside } from '@vueuse/components'

import { getEachDayInMonth } from '~resources/lib/date'

import DayMenu from './components/DayMenu.vue'
import EditableCell from './components/EditableCell.vue'
import HeadingCell from './components/HeadingCell.vue'
import MenuButton from './components/MenuButton.vue'

import { Day, getRoomsQuotasFromQuotas, RoomQuota, RoomQuotas, RoomQuotaStatus } from './lib'
import { Quota } from './lib/mock'

const props = defineProps<{
  month: Date
  quotas: Quota[]
}>()

const days = computed<Day[]>(() => getEachDayInMonth(props.month)
  .map((date): Day => ({
    key: date.toJSDate()
      .getTime()
      .toString(),
    date: date.toJSDate(),
    dayOfWeek: date.toFormat('EEE'),
    monthName: date.toFormat('MMM')
      .replace(/\.$/, ''),
    dayOfMonth: date.toFormat('d'),
  })))

const roomsQuotas = computed<RoomQuotas[]>(() =>
  getRoomsQuotasFromQuotas(props.quotas, days.value))

const firstColumn = ref<HTMLTableCellElement>()

const firstColumnWidth = computed<number | null>(() => {
  const element = firstColumn.value
  if (element === undefined) return null
  return element.clientWidth
})

const dayCellClassNameByRoomQuotaStatus: Record<RoomQuotaStatus, string> = {
  opened: 'isOpened',
  closed: 'isClosed',
}

const dayQuotaCellClassName = (status: RoomQuota['status']) =>
  ['dayQuotaCell', status !== undefined && dayCellClassNameByRoomQuotaStatus[status]]

type ActiveKey = string | null
const activeQuotaKey = ref<ActiveKey>(null)
const activeQuotaRangeKey = ref<ActiveKey>(null)
const activeReleaseDaysRangeKey = ref<ActiveKey>(null)
const activeReleaseDaysKey = ref<ActiveKey>(null)

const getActiveCellKey = (dayKey: string, roomTypeID: number) =>
  `${dayKey}-${roomTypeID}`

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

const handleQuotaValue = (value: number) => {
  console.log({ value })
}

const handleReleaseDaysValue = (value: number) => {
  console.log({ value })
}

const setHoveredDay = (key: string) => {
  hoveredDay.value = key
}

const resetHoveredDay = () => {
  hoveredDay.value = null
}

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

type IsQuotaCellInRangeParams = {
  dailyQuota: RoomQuota[]
  roomTypeID: RoomQuotas['id']
  activeKey: ActiveKey
  rangeKey: ActiveKey
  cellKey: ActiveKey
}
const isQuotaCellInRange = (params: IsQuotaCellInRangeParams): boolean => {
  const { dailyQuota, roomTypeID, activeKey, rangeKey, cellKey } = params
  let firstIndex: number = -1
  let lastIndex: number = -1
  dailyQuota.forEach(({ key }, index) => {
    if (getActiveCellKey(key, roomTypeID) === activeKey) firstIndex = index
    if (getActiveCellKey(key, roomTypeID) === rangeKey) lastIndex = index
  })
  if (firstIndex === -1 || lastIndex === -1) return false
  const part = dailyQuota.slice(firstIndex, lastIndex + 1)
  // console.log({ part }) // <- data to edit
  const found = part.find(({ key }) => getActiveCellKey(key, roomTypeID) === cellKey)
  return found !== undefined
}

const massEditTooltip = 'Зажмите Shift и кликните, чтобы задать значения для всех дней от выбранного до этого'
</script>
<template>
  <div class="quotasTable" @scroll="closeDayMenu">
    <table>
      <thead>
        <tr>
          <th ref="firstColumn" />
          <th class="headingCell" />
          <td
            v-for="{ key, dayOfWeek, monthName, dayOfMonth } in days"
            :key="key"
            class="dayCell"
            tabindex="0"
            @focusin="() => setHoveredDay(key)"
            @mouseenter="() => setHoveredDay(key)"
            @focusout="resetHoveredDay"
            @mouseleave="resetHoveredDay"
          >
            <div class="dayOfWeek">{{ dayOfWeek }}</div>
            <div class="dayOfMonth"><strong>{{ dayOfMonth }}</strong></div>
            <div class="monthName">{{ monthName }}</div>
          </td>
        </tr>
      </thead>
      <tbody>
        <template
          v-for="{
            id, label, customName, guests, count, dailyQuota,
          } in roomsQuotas"
          :key="id"
        >
          <tr class="roomTypeHeadingRow">
            <th class="roomTypeHeadingCell" rowspan="3">
              <heading-cell
                :label="label"
                :custom-name="customName"
                :guests="guests"
                :count="count"
              />
            </th>
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
                :in-range="isQuotaCellInRange({
                  dailyQuota,
                  roomTypeID: id,
                  activeKey: activeQuotaKey,
                  rangeKey: activeQuotaRangeKey,
                  cellKey: getActiveCellKey(key, id),
                })"
                @active-key="(value) => activeQuotaKey = value"
                @range-key="(value) => activeQuotaRangeKey = value"
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
                :in-range="isQuotaCellInRange({
                  dailyQuota,
                  roomTypeID: id,
                  activeKey: activeReleaseDaysKey,
                  rangeKey: activeReleaseDaysRangeKey,
                  cellKey: getActiveCellKey(key, id),
                })"
                @active-key="(value) => activeReleaseDaysKey = value"
                @range-key="(value) => activeReleaseDaysRangeKey = value"
                @value="value => handleReleaseDaysValue(value)"
              >
                {{ releaseDays }}
              </editable-cell>
            </td>
          </tr>
          <tr>
            <th class="roomTypeHeadingCell" />
            <th class="headingCell" />
            <td v-for="{ key } in dailyQuota" :key="key">
              <menu-button
                :visible="
                  hoveredRoomTypeID === null
                    ? hoveredDay === key
                    : hoveredDay === key && hoveredRoomTypeID === id
                "
                @click="(element) => {
                  openDayMenu({ trigger: element, dayKey: key, roomTypeID: id })
                }"
              />
            </td>
          </tr>
        </template>
      </tbody>
    </table>
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

  left: calc(v-bind(firstColumnWidth) * 1px);
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
