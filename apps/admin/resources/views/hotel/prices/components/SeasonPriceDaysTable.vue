<script lang="ts" setup>
import { onMounted, ref } from 'vue'

import { nanoid } from 'nanoid'

import EditableCell from './EditableCell.vue'

const props = withDefaults(defineProps<{
  data?: any
}>(), {
  data: [],
})

const localData = ref(props.data)

const id = `price-table-${nanoid()}`
const baseColumnHeight = ref<HTMLElement | null>(null)
const open = ref(false)

const setIdenticalColumnsHeights = () => {
  const containerHeight = baseColumnHeight.value?.offsetHeight
  if (!containerHeight) return
  const columns = document.querySelectorAll(`#${id} .priced`)
  columns.forEach((column) => {
    const columnElement = column as HTMLElement
    columnElement.style.height = '100px !important'
  })
}

const changeData = (item: any, value: any) => {
  if (value) {
    const itemSource = item
    itemSource.value = value
  }
}

onMounted(() => {

})
</script>

<template>
  <div class="caption">LOW SEASON 1 Январь - 31 Декабрь 2023</div>
  <div class="season-price-days-table">
    <div class="table-wrapper">
      <table :id="'id'" class="hotel-prices table table-bordered table-sm table-light">
        <thead>
          <tr>
            <th class="text-center align-middle" colspan="2">Дни недели</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th ref="baseColumnHeight" class="rate-name text-left align-middle">test1</th>
            <th class="type-name text-left align-middle">Резидент</th>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="table-wrapper">
      <table :id="'id'" class="hotel-prices table table-bordered table-sm table-light">
        <thead>
          <tr>
            <th v-for="item in localData" :key="item.id" class="align-middle text-center">Вс<br>01</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td v-for="item in localData" :key="item.id" class="priced align-middle text-center">
              <EditableCell :value="item.value" :enable-context-menu="false" @change="value => changeData(item, value)" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style lang="scss">
.table-wrapper {
  padding: 5px 0;

  &:first-child {
    table {
      width: 175px;
      min-width: 175px;
    }
  }

  &:last-child {
    overflow-x: scroll;
  }
}

.season-price-days-table {
  display: flex;
}

.priced {
  min-width: 55px;
  padding: 0 5px;
}
</style>
