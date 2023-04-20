<script lang="ts" setup>
import { computed, ref } from 'vue'

const days = Array.from({ length: 30 }, (v, i) => i + 1)

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
</script>
<template>
  <div class="root">
    <table>
      <thead>
        <tr>
          <th ref="firstColumn" />
          <th />
          <td v-for="day in days" :key="day" class="cell">
            {{ day }}
          </td>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="{
            id, label, guests, count,
          } in roomsTypes"
          :key="id"
        >
          <th>
            <p>{{ label }}</p>
            <p>Количество гостей: {{ guests }}</p>
            <p>Количество номеров: {{ count }}</p>
          </th>
          <th>
            <p>Квоты / Продано</p>
            <p>(резерв)</p>
            <p>релиз-дни</p>
          </th>
          <td v-for="day in days" :key="day">
            <p class="cell">
              1 / 0
            </p>
            <p class="cell">
              (0)
            </p>
            <p class="cell">
              0
            </p>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<style lang="scss" scoped>
.root {
  overflow: auto;
}

th {
  position: sticky;
  left: 0;
  background-color: white;
  white-space: nowrap;

  &:nth-child(2) {
    left: calc(v-bind(firstColumnWidth) * 1px);
  }
}

.cell {
  padding: 1em;
  font-size: 0.8em;
  text-align: center;
  white-space: nowrap;
}
</style>
