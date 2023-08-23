<script lang="ts" setup>
import { onMounted, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { nanoid } from 'nanoid'

import BaseDialog from '~components/BaseDialog.vue'

import EditableCell from './EditableCell.vue'

const props = withDefaults(defineProps<{
  data?: any
}>(), {
  data: [],
})

const emit = defineEmits<{
  (event: 'showSeason', item: any): void
}>()

const [isOpened, toggleModal] = useToggle()
const id = `price-table-${nanoid()}`
const baseColumnsWidth = ref<HTMLElement | null>(null)

const localData = ref(props.data)

let currentSeasonData: any = null
let currentSeasonNewPriceData: any = null

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

const changeData = (item: any, value: any) => {
  if (value) {
    currentSeasonData = item
    currentSeasonNewPriceData = value
    toggleModal()
    // item.value = value
  }
}

const onSubmitChangeData = () => {
  currentSeasonData.value = currentSeasonNewPriceData
  toggleModal(false)
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
        <td v-for="item in localData" :key="item.id" class="priced align-middle">
          <EditableCell
            :value="item.value"
            :enable-context-menu="true"
            @activated-context-menu="emit('showSeason', item)"
            @change="value => changeData(item, value)"
          />
        </td>
      </tr>
      <tr>
        <th class="type-name text-left align-middle">Не резидент</th>
        <td v-for="item in localData" :key="item.id" class="priced align-middle">
          <EditableCell
            :value="item.value"
            :enable-context-menu="true"
            @activated-context-menu="emit('showSeason', item)"
            @change="value => changeData(item, value)"
          />
        </td>
      </tr>
    </tbody>
  </table>
  <BaseDialog :opened="isOpened as boolean" @close="toggleModal(false)">
    <template #title>Вы уверены что хотите перезаписать цену на весь сезон?</template>
    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onSubmitChangeData">
        ОК
      </button>
      <button class="btn btn-cancel" type="button" @click="toggleModal(false)">Отмена</button>
    </template>
  </BaseDialog>
</template>
