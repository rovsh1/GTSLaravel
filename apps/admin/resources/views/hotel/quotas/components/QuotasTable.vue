<script lang="ts" setup>
import { computed, ref } from 'vue'

import { OnClickOutside } from '@vueuse/components'
import { DateTime } from 'luxon'

import { HotelRoomID } from '~resources/lib/api/hotel/room'
import { isMacOS } from '~resources/lib/platform'
import { plural } from '~resources/lib/plural'

import DayMenu from './DayMenu/DayMenu.vue'
import EditableCell from './EditableCell.vue'
import HeadingCell from './HeadingCell.vue'
import MenuButton from './MenuButton.vue'

import { MenuPosition, useDayMenu } from './DayMenu/use-day-menu'
import {
  ActiveKey,
  getActiveCellKey,
  MonthlyQuota,
  RoomQuota,
  RoomQuotaStatus,
  RoomRender,
} from './lib'
import { EditedQuota, QuotaRange, useQuotasTableRange } from './lib/use-range'

defineProps<{
  room: RoomRender
  monthlyQuotas: MonthlyQuota[]
  editable: boolean
}>()

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
  room_id: HotelRoomID
  dates: string[]
  count: number
}

type UpdateReleaseDaysRequest = {
  room_id: HotelRoomID
  dates: string[]
  release_days: number
}

type HandleValue<R> = (roomID: HotelRoomID, date: Date, value: number) => R

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
  const pickModifier = isMac ? '⌘' : 'Ctrl'
  return [
    'Зажмите Shift и кликните, чтобы задать значения для всех дней от выбранного до этого.',
    `Зажмите ${pickModifier} и кликните, чтобы добавить день в выборку или удалить из неё.`,
  ].join('\n')
})
</script>
<template>
  <div class="quotasRooms">
    <heading-cell
      :label="room.label"
      :custom-name="room.customName"
      :guests="room.guests"
      :count="room.count"
    />
    <div class="quotasTables">
      <div
        v-for="{ dailyQuota, monthKey, monthName, days, quotasCount } in monthlyQuotas"
        :key="monthKey"
        class="quotasTable card"
        @scroll="closeDayMenu"
      >
        <table class="card-body">
          <thead>
            <tr>
              <th class="headingCell">
                <div class="monthName">{{ monthName }}</div>
                <div>
                  <strong>
                    <template v-if="quotasCount > 0">
                      {{ plural(quotasCount, ['день', 'дня', 'дней']) }} из {{ days.length }}
                    </template>
                    <template v-else>
                      Пусто
                    </template>
                  </strong>
                </div>
              </th>
              <td
                v-for="{ key, dayOfWeek, dayOfMonth, isHoliday } in days"
                :key="key"
                class="dayCell"
                :class="{ isHoliday }"
                tabindex="0"
                @focusin="() => {
                  setHoveredRoomTypeID({ dayKey: key, roomTypeID: room.id })
                }"
                @mouseenter="() => {
                  setHoveredRoomTypeID({ dayKey: key, roomTypeID: room.id })
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
                  setHoveredRoomTypeID({ dayKey: key, roomTypeID: room.id })
                }"
                @mouseenter="() => {
                  setHoveredRoomTypeID({ dayKey: key, roomTypeID: room.id })
                }"
                @focusout="resetHoveredRoomTypeID"
                @mouseleave="resetHoveredRoomTypeID"
              >
                <editable-cell
                  :active-key="activeQuotaKey"
                  :cell-key="getActiveCellKey(key, room.id)"
                  :value="
                    editedQuota === null
                      ? quota === null ? '' : quota.toString()
                      : editedQuota.toString()
                  "
                  :max="room.count"
                  :disabled="!editable"
                  :in-range="isQuotaCellInRange(getActiveCellKey(key, room.id))"
                  @active-key="(value) => activeQuotaKey = value"
                  @range-key="(value) => setQuotaRange({
                    dailyQuota,
                    roomTypeID: room.id,
                    activeKey: activeQuotaKey,
                    rangeKey: value,
                  })"
                  @pick-key="(value) => setQuotaPick({
                    oldRange: quotaRange as QuotaRange,
                    dailyQuota,
                    roomTypeID: room.id,
                    activeKey: activeQuotaKey,
                    pickKey: value,
                  })"
                  @value="value => handleQuotaValue(room.id, date, value)"
                  @input="value => {
                    handleQuotaInput(
                      getActiveCellKey(key, room.id), value, quotaRange as QuotaRange,
                    )
                  }"
                >
                  <template
                    v-if="showEditedQuota(
                      getActiveCellKey(key, room.id), quotaRange as QuotaRange,
                    )"
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
              <th class="headingCell">Релиз-дни / Резерв</th>
              <td
                v-for="{ key, releaseDays, reserve, status, date } in dailyQuota"
                :key="key"
                :class="dayQuotaCellClassName(status)"
                :title="activeReleaseDaysKey ? massEditTooltip : undefined"
                tabindex="0"
                @focusin="() => {
                  setHoveredRoomTypeID({ dayKey: key, roomTypeID: room.id })
                }"
                @mouseenter="() => {
                  setHoveredRoomTypeID({ dayKey: key, roomTypeID: room.id })
                }"
                @focusout="resetHoveredRoomTypeID"
                @mouseleave="resetHoveredRoomTypeID"
              >
                <editable-cell
                  :active-key="activeReleaseDaysKey"
                  :cell-key="getActiveCellKey(key, room.id)"
                  :value="
                    editedReleaseDays === null
                      ? releaseDays === null ? '' : releaseDays.toString()
                      : editedReleaseDays.toString()
                  "
                  :max="30"
                  :disabled="!editable"
                  :in-range="isReleaseDaysCellInRange(getActiveCellKey(key, room.id))"
                  @active-key="(value) => activeReleaseDaysKey = value"
                  @range-key="(value) => setReleaseDaysRange({
                    dailyQuota,
                    roomTypeID: room.id,
                    activeKey: activeReleaseDaysKey,
                    rangeKey: value,
                  })"
                  @pick-key="(value) => setReleaseDaysPick({
                    oldRange: releaseDaysRange,
                    dailyQuota,
                    roomTypeID: room.id,
                    activeKey: activeReleaseDaysKey,
                    pickKey: value,
                  })"
                  @value="value => handleReleaseDaysValue(room.id, date, value)"
                  @input="value => {
                    handleReleaseDaysInput(
                      getActiveCellKey(key, room.id), value, releaseDaysRange,
                    )
                  }"
                >
                  <template
                    v-if="showEditedReleaseDays(
                      getActiveCellKey(key, room.id), releaseDaysRange,
                    )"
                  >
                    {{ editedReleaseDaysInRange?.value }}
                  </template>
                  <template v-else-if="releaseDays === null && reserve === null">
                    <template v-if="editable">—</template>
                    <template v-else>&nbsp;</template>
                  </template>
                  <template v-else-if="reserve === null">
                    {{ releaseDays }} / 0
                  </template>
                  <template v-else-if="releaseDays === null">
                    0 / {{ reserve }}
                  </template>
                  <template v-else>
                    {{ releaseDays }} / {{ reserve }}
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
                    (hoveredDay === key && hoveredRoomTypeID === room.id)
                      || (
                        menuPosition !== null
                        && getActiveCellKey(menuPosition.dayKey, menuPosition.roomTypeID)
                          === getActiveCellKey(key, room.id)
                      )
                  "
                  @click="(element) => {
                    openDayMenu({ trigger: element, dayKey: key, roomTypeID: room.id })
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

.quotasTables {
  display: flex;
  flex-flow: column;
  gap: 1em;
}

.quotasTable {
  overflow: auto;
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

  &.isHoliday {
    color: vars.$error;
  }
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

%heading-cell {
  @extend %cell;

  padding-left: 1.5em;
  text-align: right;
  white-space: nowrap;
}

.headingCell {
  @extend %heading-cell;
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
