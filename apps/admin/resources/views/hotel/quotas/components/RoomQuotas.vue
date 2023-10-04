<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import { OnClickOutside } from '@vueuse/components'

import { HotelResponse } from '~api/hotel/get'
import {
  HotelRoomQuotasCountUpdateProps,
  HotelRoomQuotasUpdateProps, HotelRoomReleaseDaysUpdateProps,
  useHotelRoomQuotasUpdate,
} from '~api/hotel/quotas/update'

import { formatDateToAPIDate } from '~lib/date'
import { plural } from '~lib/plural'

import OverlayLoading from '~components/OverlayLoading.vue'

import DayMenu from './DayMenu/DayMenu.vue'
import EditableCell from './EditableCell.vue'
import RoomHeader from './RoomHeader.vue'

import { useDayMenu } from './DayMenu/use-day-menu'
import { ActiveKey, getActiveCellKey, MonthlyQuota, RoomQuota, RoomQuotaStatus, RoomRender } from './lib'
import { EditedQuota, QuotaRange, useQuotasTableRange } from './lib/use-range'

const props = defineProps<{
  hotel: HotelResponse
  room: RoomRender
  monthlyQuotas: MonthlyQuota[]
  editable: boolean
  waitingLoadData: boolean
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

const dayCellClassNameByRoomQuotaStatus: Record<RoomQuotaStatus, string> = {
  opened: 'isOpened',
  closed: 'isClosed',
}

const dayQuotaCellClassName = (status: RoomQuota['status']) =>
  ['dayQuotaCell', status !== null && dayCellClassNameByRoomQuotaStatus[status]]

const allMonthsDailyQuotas = computed(() =>
  props.monthlyQuotas.map(({ dailyQuota }) => dailyQuota).flat())

const editedQuotasCount = ref<number | null>(null)
const activeQuotasCountKey = ref<ActiveKey>(null)
const editedQuotasCountInRange = ref<EditedQuota | null>(null)
const {
  rangeRef: quotasCountRange,
  setRange: setQuotasCountRange,
  setPick: setQuotasCountPick,
  isCellInRange: isQuotasCountCellInRange,
  handleInput: handleQuotasCountInput,
  showEdited: showEditedQuotasCount,
  setEdited: setEditedInRangeQuotasCount,
} = useQuotasTableRange({
  roomQuotas: allMonthsDailyQuotas,
  editedRef: editedQuotasCount,
  activeKey: activeQuotasCountKey,
  editedInRange: editedQuotasCountInRange,
})

const goEditQuotas = ref<boolean>(false)

const editedReleaseDays = ref<number | null>(null)
const activeReleaseDaysKey = ref<ActiveKey>(null)
const editedReleaseDaysInRange = ref<EditedQuota | null>(null)
const {
  rangeRef: releaseDaysRange,
  // setRange: setReleaseDaysRange,
  // setPick: setReleaseDaysPick,
  // isCellInRange: isReleaseDaysCellInRange,
  // handleInput: handleReleaseDaysInput,
  // showEdited: showEditedReleaseDays,
  // setEdited: setEditedInRangeReleaseDays,
} = useQuotasTableRange({
  roomQuotas: allMonthsDailyQuotas,
  editedRef: editedReleaseDays,
  activeKey: activeReleaseDaysKey,
  editedInRange: editedReleaseDaysInRange,
})

const resetActiveKey = () => {
  activeQuotasCountKey.value = null
  editedQuotasCount.value = null
  activeReleaseDaysKey.value = null
  editedReleaseDays.value = null
  dayMenuActionCompleted.value = false
}

const {
  dayMenuRef,
  dayMenuPosition,
  openDayMenu,
  closeDayMenu,
} = useDayMenu()

const getDatesFromRange = (range: QuotaRange): Date[] | null => (range === null
  ? null
  : range.quotas.map(({ date }) => date))

const dayMenuDates = computed<Date[] | null>(() => {
  const fromQuotasCount = getDatesFromRange(quotasCountRange.value)
  const fromReleaseDaysRange = getDatesFromRange(releaseDaysRange.value)
  const fromMenuPosition = dayMenuPosition.value === null ? null : [dayMenuPosition.value.date]

  return fromQuotasCount || fromReleaseDaysRange || fromMenuPosition
})

const dayMenuDone = () => {
  closeDayMenu()
  dayMenuActionCompleted.value = true
  emit('update', props.room.id)
}

type HandleValue<R> = (date: Date, value: number) => R

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
const getQuotasCountPayload: GetQuotasCountPayload = (date, value) => {
  const common = {
    kind: 'count' as const,
    hotelID: props.hotel.id,
    roomID: props.room.id,
  }
  const range = quotasCountRange.value
  if (range === null) {
    return {
      ...common,
      dates: [formatDateToAPIDate(date)],
      count: value,
    }
  }
  const { quotas } = range
  return {
    ...common,
    dates: quotas.map((quota) => formatDateToAPIDate(quota.date)),
    count: value,
  }
}

const handleQuotaValue: HandleValue<void> = (date, value) => {
  hotelRoomQuotasUpdateProps.value = getQuotasCountPayload(date, value)
  executeHotelRoomQuotasUpdate()
}

type GetReleaseDaysPayload = HandleValue<HotelRoomReleaseDaysUpdateProps>
const getReleaseDaysPayload: GetReleaseDaysPayload = (date, value) => {
  const common = {
    kind: 'releaseDays' as const,
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
  hotelRoomQuotasUpdateProps.value = getReleaseDaysPayload(date, value)
  executeHotelRoomQuotasUpdate()
}

</script>
<template>
  <div>
    <div class="roomHeader">
      <room-header :label="room.label" :guests="room.guests" :count="room.count" />
    </div>
    <div class="quotasTables">
      <OverlayLoading v-if="isHotelRoomQuotasUpdateFetching || waitingLoadData" />
      <div
        class="quotasTable card"
        @scroll="closeDayMenu"
      >
        <table class="card-body">
          <thead>
            <tr>
              <th class="headingCell">
                Месяц
              </th>
              <td
                v-for="{ monthKey, monthName, days, quotasCount } in monthlyQuotas"
                :key="monthKey + '-month'"
                :colspan="days.length"
                class="headingCell"
                :class="[monthlyQuotas.length > 1 ? 'month-splitter' : '']"
              >
                <div class="monthName">
                  {{ monthName }}
                  <strong>
                    <template v-if="quotasCount > 0">
                      ({{ plural(quotasCount, ['день', 'дня', 'дней']) }} из {{ days.length }})
                    </template>
                    <template v-else>
                      (Пусто)
                    </template>
                  </strong>
                </div>
              </td>
            </tr>
            <tr>
              <th class="headingCell">
                День / Число
              </th>
              <template v-for="{ monthKey, days } in monthlyQuotas" :key="monthKey.key + '-days'">
                <template
                  v-for="(day, index) in days"
                  :key="day.key + '-day'"
                >
                  <td
                    class="dayCell"
                    :class="{ isHoliday: day.isHoliday, 'month-splitter': monthlyQuotas.length > 1 && index === days.length - 1 }"
                    tabindex="0"
                  >
                    <div class="dayOfWeek">{{ day.dayOfWeek }}</div>
                    <div class="dayOfMonth"><strong>{{ day.dayOfMonth }}</strong></div>
                  </td>
                </template>
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
              <template v-for="{ monthKey, dailyQuota } in monthlyQuotas" :key="monthKey.key + '-days'">
                <td
                  v-for="{ key, quota, status, date, releaseDays, sold, reserve } in dailyQuota"
                  :key="key"
                  :class="dayQuotaCellClassName(status)"
                  tabindex="0"
                >
                  <div class="content-height">
                    <button
                      v-if="!goEditQuotas"
                      type="button"
                      @click="goEditQuotas = true"
                    >
                      {{ editedQuotasCount === null
                        ? (quota === null ? '' : quota.toString())
                        : editedQuotasCount.toString() }}
                    </button>
                    <editable-cell
                      v-else
                      :day-menu-ref="dayMenuElementRef"
                      :day-menu-action-completed="dayMenuActionCompleted || isOpeningAnotherRoomDayMenu"
                      :active-key="activeQuotasCountKey"
                      :cell-key="getActiveCellKey(key, room.id)"
                      :value="editedQuotasCount === null
                        ? (quota === null ? '' : quota.toString())
                        : editedQuotasCount.toString()
                      "
                      :max="room.count"
                      :range-error="(min, max) => `Есть только ${max} таких комнат`"
                      :disabled="!editable || isHotelRoomQuotasUpdateFetching"
                      :in-range="isQuotasCountCellInRange(getActiveCellKey(key, room.id))"
                      :init-value="quota === null ? '' : quota.toString()"
                      @reset-edited-value-to-init="(value) => {
                        setEditedInRangeQuotasCount(key, value)
                        editedQuotasCount = value
                      }"
                      @active-key="(value: ActiveKey) => {
                        activeQuotasCountKey = value
                        activeReleaseDaysKey = null
                      }"
                      @reset="resetActiveKey"
                      @range-key="(value: ActiveKey) => {
                        setQuotasCountRange({
                          dailyQuota,
                          roomTypeID: room.id,
                          activeKey: activeQuotasCountKey,
                          rangeKey: value,
                        })
                      }"
                      @pick-key="(value: ActiveKey) => {
                        setQuotasCountPick({
                          oldRange: quotasCountRange as QuotaRange,
                          roomTypeID: room.id,
                          activeKey: activeQuotasCountKey,
                          pickKey: value,
                        })
                      }"
                      @value="value => handleQuotaValue(date, value)"
                      @input="value => handleQuotasCountInput(getActiveCellKey(key, room.id), value)"
                      @context-menu="(element) => {
                        openDayMenu({
                          trigger: element, date, dayKey: key, roomTypeID: room.id,
                        })
                        emit('open-day-menu-in-another-room', room.id)
                      }"
                    >
                      <template v-if="showEditedQuotasCount(getActiveCellKey(key, room.id))">
                        {{ editedQuotasCountInRange?.value }}
                      </template>
                      <template v-else-if="quota === null">
                        <template v-if="editable">—</template>
                        <template v-else>&nbsp;</template>
                      </template>
                      <template v-else>
                        {{ quota }}
                      </template>
                    </editable-cell>
                  </div>
                  <div class="content-height">
                    <span v-if="true">
                      {{ editedReleaseDays === null
                        ? releaseDays === null ? '' : releaseDays.toString()
                        : editedReleaseDays.toString() }}
                    </span>
                  </div>
                  <div class="content-height">
                    <template v-if="sold === null && reserve === null">
                  &nbsp;
                    </template>
                    <template v-else-if="sold === null">
                      0 / {{ reserve }}
                    </template>
                    <template v-else-if="reserve === null">
                      {{ sold }} / 0
                    </template>
                    <template v-else>
                      {{ sold }} / {{ reserve }}
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

  text-align: center;
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
}

.staticDataCell {
  @extend %data-cell;

  padding: 0.731em;
}

.month-splitter {
  border-right: 1px solid #000;
}

tr td:last-child {
  border-right: none;
}

.content-height {
  height: 36px;
}
</style>
