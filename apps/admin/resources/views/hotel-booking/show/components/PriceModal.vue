<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  opened: MaybeRef<boolean>
  header: string
  label: string
  value?: number
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', value: number | undefined): void
}>()

const isChanged = ref(false)
const tempValue = ref<number | undefined>()
const localValue = computed({
  get: () => (!isChanged.value ? props.value : tempValue.value),
  set: (value: number | undefined) => {
    isChanged.value = true
    tempValue.value = value
  },
})

const handleSubmit = () => {
  emit('submit', localValue.value)
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    @close="$emit('close')"
  >
    <template #title>{{ header }}</template>

    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <label for="price">{{ label }}</label>
        <input id="price" v-model.number="localValue" type="number" class="form-control">
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="handleSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
