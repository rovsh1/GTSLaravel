<script lang="ts" setup>
import { onMounted, ref } from 'vue'

import { nanoid } from 'nanoid'

import RoomPriceTableEditableCell from './RoomPriceTableEditableCell.vue'

const props = withDefaults(defineProps<{
  data?: any
}>(), {
  data: [],
})

const id = `price-table-${nanoid()}`
const baseColumnsWidth = ref<HTMLElement | null>(null)
const open = ref(false)

const setIdenticalColumnsWidth = (columnCount: number) => {
  const containerWidth = baseColumnsWidth.value?.offsetWidth
  if (!containerWidth) return
  const calculateColumnWidth = containerWidth / columnCount
  const columns = document.querySelectorAll(`#${id} .priced`)
  columns.forEach((column) => {
    const columnElement = column as HTMLElement
    columnElement.style.width = `${calculateColumnWidth}px`
  })
}

onMounted(() => {
  setIdenticalColumnsWidth(4)
})

</script>

<template>
  <table :id="id" ref="containerElement" class="hotel-prices table table-bordered table-sm table-light">
    <caption>Стоимость</caption>
    <thead>
      <tr>
        <th class="text-center align-middle" colspan="2">Сезон</th>
        <th ref="baseColumnsWidth" class="text-center align-middle" colspan="4">
          <div class="name">LOW SEASON</div>
          <div class="period">1 Январь - 31 Декабрь 2023</div>
        </th>
      </tr>
      <tr>
        <th class="text-center align-middle" colspan="2">Количество гостей</th>
        <th class="text-center align-middle">1</th>
        <th class="text-center align-middle">2</th>
        <th class="text-center align-middle">3</th>
        <th class="text-center align-middle">4</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th ref="susctractElement1" rowspan="2" class="rate-name text-left align-middle">test1</th>
        <th ref="susctractElement2" class="type-name text-left align-middle">Резидент</th>
        <td
          class="priced align-middle"
          @click.stop="open = !open"
          @keydown="open = !open"
        >
          <RoomPriceTableEditableCell :is-edit="open" />
        </td>
        <td
          class="priced align-middle"
        >
          <RoomPriceTableEditableCell :is-edit="false" />
        </td>
        <td
          class="priced align-middle"
        >
          <RoomPriceTableEditableCell :is-edit="false" />
        </td>
        <td
          class="priced align-middle"
        >
          <RoomPriceTableEditableCell :is-edit="false" />
        </td>
      </tr>
      <tr>
        <th class="type-name text-left align-middle">Не резидент</th>
        <td
          class="priced align-middle"
        >
          <RoomPriceTableEditableCell :is-edit="false" />
        </td>
        <td
          class="priced align-middle"
        >
          <RoomPriceTableEditableCell :is-edit="false" />
        </td>
        <td
          class="priced align-middle"
        >
          <RoomPriceTableEditableCell :is-edit="false" />
        </td>
        <td
          class="priced align-middle"
        >
          <RoomPriceTableEditableCell :is-edit="false" />
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style lang="scss" scoped>
table {
  font-size: 11px;

  tr {
    th.rate-name {
      width: 200px;
    }

    th.type-name {
      width: 120px;
    }

    &:hover td {
      box-shadow: none
    }

    td {
      background-color: #fff;
      white-space: nowrap;
      cursor: pointer;

      &.priced {
        padding: 0;
        line-height: 32px;
        text-align: right;
      }

      &:hover {
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 25%);
      }
    }
  }
}
</style>
