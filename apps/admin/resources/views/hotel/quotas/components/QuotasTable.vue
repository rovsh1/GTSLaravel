<script lang="ts" setup>
import { computed, ref } from 'vue'

import { OnClickOutside } from '@vueuse/components'
import { DateTime } from 'luxon'

import { getEachDayInMonth } from '~resources/lib/date'
import { isMacOS } from '~resources/lib/platform'

import DayMenu from './DayMenu/DayMenu.vue'
import EditableCell from './EditableCell.vue'
import HeadingCell from './HeadingCell.vue'
import MenuButton from './MenuButton.vue'

import { MenuPosition, useDayMenu } from './DayMenu/use-day-menu'
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
import { EditedQuota, QuotaRange, useQuotasTableRange } from './lib/use-range'

const props = defineProps<{
  month: Date
  quotas: Quota[]
  editable: boolean
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
  ['dayQuotaCell', status !== null && dayCellClassNameByRoomQuotaStatus[status]]

const editedQuota = ref<number | null>(null)
const quotaRange = ref<QuotaRange>(null)
const activeQuotaKey = ref<ActiveKey>(null)
const editedQuotaInRange = ref<EditedQuota | null>(null)
const {
  setRange: setQuotaRange,
  setPick: setQuotaPick,
  isCellInRange: isQuotaCellInRange,
  handleInput: handleQuotaInput,
  showEdited: showEditedQuota,
} = useQuotasTableRange({
  editedRef: editedQuota,
  rangeRef: quotaRange,
  activeKey: activeQuotaKey,
  editedInRange: editedQuotaInRange,
})

const editedReleaseDays = ref<number | null>(null)
const releaseDaysRange = ref<QuotaRange>(null)
const activeReleaseDaysKey = ref<ActiveKey>(null)
const editedReleaseDaysInRange = ref<EditedQuota | null>(null)
const {
  setRange: setReleaseDaysRange,
  setPick: setReleaseDaysPick,
  isCellInRange: isReleaseDaysCellInRange,
  handleInput: handleReleaseDaysInput,
  showEdited: showEditedReleaseDays,
} = useQuotasTableRange({
  editedRef: editedReleaseDays,
  rangeRef: releaseDaysRange,
  activeKey: activeReleaseDaysKey,
  editedInRange: editedReleaseDaysInRange,
})

const {
  hoveredDay,
  hoveredRoomTypeID,
  menuRef,
  menuPosition,
  openDayMenu,
  closeDayMenu,
  setHoveredRoomTypeID,
  resetHoveredRoomTypeID,
} = useDayMenu()

const formatDateToAPIDate = (date: Date): string => DateTime
  .fromJSDate(date).toFormat('yyyy-LL-dd')

type UpdateQuotasRequest = {
  room_id: RoomID
  dates: string[]
  count: number
}

type UpdateReleaseDaysRequest = {
  room_id: RoomID
  dates: string[]
  release_days: number
}

type HandleValue<R> = (roomID: RoomID, date: Date, value: number) => R

const handleQuotaValue: HandleValue<UpdateQuotasRequest> = (roomID, date, value) => {
  const range = quotaRange.value
  if (range === null) {
    const request: UpdateQuotasRequest = {
      room_id: roomID,
      dates: [formatDateToAPIDate(date)],
      count: value,
    }
    console.log('quota: single value', { request })
    return request
  }
  const { quotas } = range
  const request: UpdateQuotasRequest = {
    room_id: roomID,
    dates: quotas.map((quota) => formatDateToAPIDate(quota.date)),
    count: value,
  }
  console.log('quota: multiple value', { request })
  return request
}

const handleReleaseDaysValue: HandleValue<UpdateReleaseDaysRequest> = (roomID, date, value) => {
  const range = releaseDaysRange.value
  if (range === null) {
    const request: UpdateReleaseDaysRequest = {
      room_id: roomID,
      dates: [formatDateToAPIDate(date)],
      release_days: value,
    }
    console.log('release days: single value', { request })
    return request
  }
  const { quotas } = range
  const request: UpdateReleaseDaysRequest = {
    room_id: roomID,
    dates: quotas.map((quota) => formatDateToAPIDate(quota.date)),
    release_days: value,
  }
  console.log('release days: multiple value', { request })
  return request
}

const isMac = isMacOS()

const massEditTooltip = computed(() => {
  const pickModifier = isMac ? 'CMD' : 'Ctrl'
  return [
    'Зажмите Shift и кликните, чтобы задать значения для всех дней от выбранного до этого.',
    `Зажмите ${pickModifier} и кликните, чтобы добавить день в выборку или удалить из неё.`,
  ].join('\n')
})
</script>
<template>
  <div class="quotasRooms">
    <div
      v-for="{
        id, label, customName, guests, count, dailyQuota,
      } in roomsQuotas"
      :key="id"
    >
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
                v-for="{ key, quota, sold, status, date } in dailyQuota"
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
                  :value="
                    editedQuota === null
                      ? quota === null ? '' : quota.toString()
                      : editedQuota.toString()
                  "
                  :max="count"
                  :disabled="!editable"
                  :in-range="isQuotaCellInRange(getActiveCellKey(key, id))"
                  @active-key="(value) => activeQuotaKey = value"
                  @range-key="(value) => setQuotaRange({
                    dailyQuota,
                    roomTypeID: id,
                    activeKey: activeQuotaKey,
                    rangeKey: value,
                  })"
                  @pick-key="(value) => setQuotaPick({
                    oldRange: quotaRange as QuotaRange,
                    dailyQuota,
                    roomTypeID: id,
                    activeKey: activeQuotaKey,
                    pickKey: value,
                  })"
                  @value="value => handleQuotaValue(id, date, value)"
                  @input="value => {
                    handleQuotaInput(getActiveCellKey(key, id), value, quotaRange as QuotaRange)
                  }"
                >
                  <template
                    v-if="showEditedQuota(getActiveCellKey(key, id), quotaRange as QuotaRange)"
                  >
                    {{ editedQuotaInRange?.value }}
                  </template>
                  <template v-else-if="quota === null && sold === null">
                    <template v-if="editable">—</template>
                    <template v-else>&nbsp;</template>
                  </template>
                  <template v-else-if="sold === null">
                    {{ quota }} / 0
                  </template>
                  <template v-else-if="quota === null">
                    0 / {{ sold }}
                  </template>
                  <template v-else>
                    {{ quota }} / {{ sold }}
                  </template>
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
                <template v-if="reserve === null">
                  &nbsp;
                </template>
                <template v-else>
                  ({{ reserve }})
                </template>
              </td>
            </tr>
            <tr>
              <th class="headingCell">релиз-дни</th>
              <td
                v-for="{ key, releaseDays, status, date } in dailyQuota"
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
                  :value="
                    editedReleaseDays === null
                      ? releaseDays === null ? '' : releaseDays.toString()
                      : editedReleaseDays.toString()
                  "
                  :max="30"
                  :disabled="!editable"
                  :in-range="isReleaseDaysCellInRange(getActiveCellKey(key, id))"
                  @active-key="(value) => activeReleaseDaysKey = value"
                  @range-key="(value) => setReleaseDaysRange({
                    dailyQuota,
                    roomTypeID: id,
                    activeKey: activeReleaseDaysKey,
                    rangeKey: value,
                  })"
                  @pick-key="(value) => setReleaseDaysPick({
                    oldRange: releaseDaysRange,
                    dailyQuota,
                    roomTypeID: id,
                    activeKey: activeReleaseDaysKey,
                    pickKey: value,
                  })"
                  @value="value => handleReleaseDaysValue(id, date, value)"
                  @input="value => {
                    handleReleaseDaysInput(getActiveCellKey(key, id), value, releaseDaysRange)
                  }"
                >
                  <template
                    v-if="showEditedReleaseDays(getActiveCellKey(key, id), releaseDaysRange)"
                  >
                    {{ editedReleaseDaysInRange?.value }}
                  </template>
                  <template v-else-if="releaseDays === null">
                    <template v-if="editable">—</template>
                    <template v-else>&nbsp;</template>
                  </template>
                  <template v-else>
                    {{ releaseDays }}
                  </template>
                </editable-cell>
              </td>
            </tr>
            <tr>
              <th class="headingCell" />
              <td
                v-for="{ key } in dailyQuota"
                :key="key"
                class="menuButtonCell"
                :class="{ isInvisible: !editable }"
              >
                <menu-button
                  :visible="
                    (hoveredDay === key && hoveredRoomTypeID === id)
                      || (
                        menuPosition !== null
                        && getActiveCellKey(menuPosition.dayKey, menuPosition.roomTypeID)
                          === getActiveCellKey(key, id)
                      )
                  "
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
    <Teleport v-if="menuRef !== null && menuPosition !== null" to="body">
      <OnClickOutside @trigger="closeDayMenu">
        <day-menu
          :menu-ref="menuRef"
          :menu-day-key="(menuPosition as MenuPosition).dayKey"
          @close="closeDayMenu"
          @focusin="() => setHoveredRoomTypeID(menuPosition as MenuPosition)"
          @mouseenter="() => setHoveredRoomTypeID(menuPosition as MenuPosition)"
          @focusout="resetHoveredRoomTypeID"
          @mouseleave="resetHoveredRoomTypeID"
        />
      </OnClickOutside>
    </Teleport>
  </div>
</template>
<style lang="scss" scoped>
@use '~resources/sass/variables' as vars;
@use 'shared' as shared;

.quotasRooms {
  display: flex;
  flex-flow: column;
  gap: 2em;
}

.quotasTable {
  overflow: auto;
  border: 1px solid lightgray;
  border-radius: 0.6em;
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

  padding-left: 1.5em;
  text-align: right;
  white-space: nowrap;
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

.menuButtonCell {
  &.isInvisible {
    opacity: 0;
    pointer-events: none;
  }
}
</style>
