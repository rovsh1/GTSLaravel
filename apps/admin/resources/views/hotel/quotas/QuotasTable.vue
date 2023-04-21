<script lang="ts" setup>
import { computed, ref } from 'vue'

import { getEachDayInMonth } from '~resources/lib/date'
import EditableCell from '~resources/views/hotel/quotas/components/EditableCell.vue'

const month = new Date()

type Day = {
  key: string
  dayOfWeek: string
  monthName: string
  dayOfMonth: string
  quota: number
  sold: number
  reserve: number
  releaseDays: number
}

const days = computed<Day[]>(() => getEachDayInMonth(month)
  .map((date) => ({
    key: date.toJSDate().getTime().toString(),
    dayOfWeek: date.toFormat('EEE'),
    monthName: date.toFormat('MMM').replace(/\.$/, ''),
    dayOfMonth: date.toFormat('d'),
    quota: 1,
    sold: 0,
    reserve: 0,
    releaseDays: 0,
  })))

interface RoomType {
  id: number
  label: string
  guests: number
  count: number
}

const roomsTypes: RoomType[] = [
  {
    id: 183,
    label: 'Стандартный двухместный',
    guests: 2,
    count: 10,
  },
  {
    id: 184,
    label: 'Стандартный одноместный',
    guests: 1,
    count: 15,
  },
]

const firstColumn = ref<HTMLTableCellElement>()

const firstColumnWidth = computed<number | null>(() => {
  const element = firstColumn.value
  if (element === undefined) return null
  return element.clientWidth
})

type ActiveKey = string | null
const activeQuotaKey = ref<ActiveKey>(null)
const activeReleaseDaysKey = ref<ActiveKey>(null)

const getActiveCellKey = (dayKey: string, roomTypeID: number) =>
  `${dayKey}-${roomTypeID}`
</script>
<template>
  <div class="root">
    <table>
      <thead>
        <tr>
          <th ref="firstColumn" />
          <th class="headingCell" />
          <td
            v-for="{
              key, dayOfWeek, monthName, dayOfMonth,
            } in days"
            :key="key"
            class="dayCell"
          >
            <div class="dayOfWeek">
              {{ dayOfWeek }}
            </div>
            <div class="dayOfMonth">
              <strong>{{ dayOfMonth }}</strong>
            </div>
            <div class="monthName">
              {{ monthName }}
            </div>
          </td>
        </tr>
      </thead>
      <tbody>
        <template
          v-for="{
            id, label, guests, count,
          } in roomsTypes"
          :key="id"
        >
          <tr class="roomTypeHeadingRow">
            <th class="roomTypeHeadingCell" rowspan="3">
              <div><strong>{{ label }}</strong> ({{ id }})</div>
              <dl class="roomTypeStats">
                <dt class="roomTypeStatLabel">
                  Количество гостей:
                </dt>
                <dd class="roomTypeStatValue">
                  {{ guests }}
                </dd>
                <dt class="roomTypeStatLabel">
                  Количество номеров:
                </dt>
                <dd class="roomTypeStatValue">
                  {{ count }}
                </dd>
              </dl>
            </th>
            <th class="headingCell">
              Квоты / Продано
            </th>
            <td v-for="{ key, quota, sold } in days" :key="key">
              <editable-cell
                :active-key="activeQuotaKey"
                :cell-key="getActiveCellKey(key, id)"
                :value="quota.toString()"
                @active-key="(value) => activeQuotaKey = value"
              >
                {{ quota }} / {{ sold }}
              </editable-cell>
            </td>
          </tr>
          <tr>
            <th class="reserveHeadingCell">
              (резерв)
            </th>
            <td
              v-for="{ key, reserve } in days"
              :key="key"
              class="reserveDataCell"
            >
              ({{ reserve }})
            </td>
          </tr>
          <tr>
            <th class="headingCell">
              релиз-дни
            </th>
            <td
              v-for="{ key, releaseDays } in days"
              :key="key"
            >
              <editable-cell
                :active-key="activeReleaseDaysKey"
                :cell-key="getActiveCellKey(key, id)"
                :value="releaseDays.toString()"
                @active-key="(value) => activeReleaseDaysKey = value"
              >
                {{ releaseDays }}
              </editable-cell>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>
<style lang="scss" scoped>
@use '~resources/sass/variables' as vars;
@use './components/shared' as shared;

.root {
  overflow: auto;
  font-size: 0.8em;
}

th {
  position: sticky;
  left: 0;
  background-color: white;
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

.roomTypeStats {
  display: grid;
  grid-template-columns: repeat(2, min-content);
  gap: 0 0.25em;
  margin: unset;
}

.roomTypeStatLabel {
  color: gray;
}

.roomTypeStatValue {
  margin: unset;
  color: vars.$error;
  font-weight: bold;
}
</style>
