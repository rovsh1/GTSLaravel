<script lang="ts" setup>
import { computed, ref, watch } from 'vue'

import { getEachDayInMonth } from '~resources/lib/date'

const month = new Date()

type Day = {
  key: string
  dayOfWeek: string
  monthName: string
  dayOfMonth: string
  quota: number
  sold: number
}

const days = computed<Day[]>(() => getEachDayInMonth(month)
  .map((date) => ({
    key: date.toJSDate().getTime().toString(),
    dayOfWeek: date.toFormat('EEE'),
    monthName: date.toFormat('MMM').replace(/\.$/, ''),
    dayOfMonth: date.toFormat('d'),
    quota: 1,
    sold: 0,
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

const activeQuotaKey = ref<string | null>(null)

const getActiveQuotaKey = (dayKey: string, roomTypeID: number) =>
  `${dayKey}-${roomTypeID}`

const quotaInputRef = ref<HTMLInputElement>()

watch(quotaInputRef, (element) => element?.focus())
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
              <input
                v-if="activeQuotaKey === getActiveQuotaKey(key, id)"
                :ref="(element) => quotaInputRef = element as HTMLInputElement"
                :value="quota"
                class="editableCellInput"
                @blur="() => activeQuotaKey = null"
              >
              <button
                v-else
                type="button"
                class="editableDataCell"
                @click="() => activeQuotaKey = getActiveQuotaKey(key, id)"
              >
                {{ quota }} / {{ sold }}
              </button>
            </td>
          </tr>
          <tr>
            <th class="headingCell">
              (резерв)
            </th>
            <td v-for="{ key } in days" :key="key" class="dataCell">
              (0)
            </td>
          </tr>
          <tr>
            <th class="headingCell">
              релиз-дни
            </th>
            <td v-for="{ key } in days" :key="key" class="dataCell">
              0
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>
<style lang="scss" scoped>
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
  padding: 0.5em;
}

.dayCell {
  @extend %cell;

  min-width: 4em;
  text-align: center;
}

%data-cell {
  @extend %cell;

  text-align: center;
  white-space: nowrap;
}

.editableDataCell {
  @extend %data-cell;

  width: 100%;
  border: 2px solid transparent;
  background-color: unset;

  &:hover {
    background-color: rgba(black, 0.1);
  }
}

.editableCellInput {
  @extend %data-cell;

  width: 100%;
  font-size: inherit;
}

.dataCell {
  @extend %data-cell;
}

.headingCell {
  @extend %cell;

  left: calc(v-bind(firstColumnWidth) * 1px);
  text-align: right;
  white-space: nowrap;
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
  color: red;
  font-weight: bold;
}
</style>
