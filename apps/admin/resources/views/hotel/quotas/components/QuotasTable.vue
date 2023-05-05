<script lang="ts" setup>
import { ref } from 'vue'

import { OnClickOutside } from '@vueuse/components'

import { HotelResponse } from '~resources/lib/api/hotel/hotel'
import {
  HotelRoomQuotasUpdatePayload,
  HotelRoomQuotasUpdateProps,
  HotelRoomReleaseDaysUpdateProps,
  useHotelRoomQuotasUpdate, useHotelRoomReleaseDaysUpdate,
} from '~resources/lib/api/hotel/quotas'
import { formatDateToAPIDate } from '~resources/lib/date'
import { plural } from '~resources/lib/plural'

import DayMenu from './DayMenu/DayMenu.vue'
import EditableCell from './EditableCell.vue'
import RoomHeader from './RoomHeader.vue'

import { useDayMenu } from './DayMenu/use-day-menu'
import {
  ActiveKey,
  getActiveCellKey,
  MonthlyQuota,
  RoomQuota,
  RoomQuotaStatus,
  RoomRender,
} from './lib'
import { EditedQuota, QuotaRange, useQuotasTableRange } from './lib/use-range'

const props = defineProps<{
  hotel: HotelResponse
  room: RoomRender
  monthlyQuotas: MonthlyQuota[]
  editable: boolean
}>()

const emit = defineEmits<{
  (event: 'update'): void
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

const resetActiveKey = () => {
  activeQuotaKey.value = null
  activeReleaseDaysKey.value = null
}

const {
  menuRef,
  menuPosition,
  openDayMenu,
  closeDayMenu,
} = useDayMenu()

type HandleValue<R> = (date: Date, value: number) => R

const hotelRoomQuotasUpdateProps = ref<HotelRoomQuotasUpdateProps | null>(null)

const {
  execute: executeHotelRoomQuotasUpdate,
  onFetchFinally: onHotelRoomQuotasUpdateFinally,
} = useHotelRoomQuotasUpdate(hotelRoomQuotasUpdateProps)

const getQuotaPayload: HandleValue<HotelRoomQuotasUpdatePayload> = (date, value) => {
  const range = quotaRange.value
  if (range === null) {
    return {
      dates: [formatDateToAPIDate(date)],
      count: value,
    }
  }
  const { quotas } = range
  return {
    dates: quotas.map((quota) => formatDateToAPIDate(quota.date)),
    count: value,
  }
}

const handleQuotaValue: HandleValue<void> = (date, value) => {
  const { dates, count } = getQuotaPayload(date, value)
  hotelRoomQuotasUpdateProps.value = {
    hotelID: props.hotel.id,
    roomID: props.room.id,
    dates,
    count,
  }
  executeHotelRoomQuotasUpdate()
}

onHotelRoomQuotasUpdateFinally(() => {
  hotelRoomQuotasUpdateProps.value = null
  emit('update')
})

const hotelRoomReleaseDaysUpdateProps = ref<HotelRoomReleaseDaysUpdateProps | null>(null)

const {
  execute: executeHotelRoomReleaseDaysUpdate,
  onFetchFinally: onHotelRoomReleaseDaysUpdateFinally,
} = useHotelRoomReleaseDaysUpdate(hotelRoomReleaseDaysUpdateProps)

const getReleaseDaysPayload: HandleValue<HotelRoomReleaseDaysUpdateProps> = (date, value) => {
  const common = {
    hotelID: props.hotel.id,
    roomID: props.room.id,
  }
  const range = releaseDaysRange.value
  if (range === null) {
    return {
      ...common,
      dates: [formatDateToAPIDate(date)],
      releaseDays: value,
    }
  }
  const { quotas } = range
  return {
    ...common,
    dates: quotas.map((quota) => formatDateToAPIDate(quota.date)),
    releaseDays: value,
  }
}

const handleReleaseDaysValue: HandleValue<void> = (date, value) => {
  hotelRoomReleaseDaysUpdateProps.value = getReleaseDaysPayload(date, value)
  executeHotelRoomReleaseDaysUpdate()
}

onHotelRoomReleaseDaysUpdateFinally(() => {
  hotelRoomReleaseDaysUpdateProps.value = null
  emit('update')
})
</script>
<template>
  <div class="quotasRooms">
    <room-header
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
                tabindex="0"
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
                  @active-key="(value) => {
                    activeQuotaKey = value
                    activeReleaseDaysKey = null
                  }"
                  @reset="resetActiveKey"
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
                  @value="value => handleQuotaValue(date, value)"
                  @input="value => {
                    handleQuotaInput(
                      getActiveCellKey(key, room.id), value, quotaRange as QuotaRange,
                    )
                  }"
                  @context-menu="(element) => {
                    openDayMenu({ trigger: element, dayKey: key, roomTypeID: room.id })
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
                tabindex="0"
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
                  @active-key="(value) => {
                    activeReleaseDaysKey = value
                    activeQuotaKey = null
                  }"
                  @reset="resetActiveKey"
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
                  @value="value => handleReleaseDaysValue(date, value)"
                  @input="value => {
                    handleReleaseDaysInput(
                      getActiveCellKey(key, room.id), value, releaseDaysRange,
                    )
                  }"
                  @context-menu="(element) => {
                    openDayMenu({ trigger: element, dayKey: key, roomTypeID: room.id })
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
          </tbody>
        </table>
      </div>
    </div>
    <Teleport v-if="menuRef !== null && menuPosition !== null" to="body">
      <OnClickOutside @trigger="closeDayMenu">
        <day-menu
          :menu-ref="menuRef"
          :menu-day-key="menuPosition ? menuPosition.dayKey : null"
          @close="closeDayMenu"
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
</style>
