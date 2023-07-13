<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import TimeSelect from '~resources/views/hotel/settings/components/TimeSelect.vue'

import { MarkupCondition, Time } from '~api/hotel/markup-settings'

import BaseDialog from '~components/BaseDialog.vue'

const props = withDefaults(defineProps<{
  value?: MarkupCondition
  opened: MaybeRef<boolean>
  title: string
  loading?: MaybeRef<boolean>
  isTimeAvailable?: (time: Time) => boolean
  minFrom?: Time
  maxFrom?: Time
  minTo?: Time
  maxTo?: Time
}>(), {
  loading: false,
  value: undefined,
  minFrom: undefined,
  maxFrom: undefined,
  minTo: undefined,
  maxTo: undefined,
  isTimeAvailable: undefined,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', value: MarkupCondition): void
}>()

const localValue = computed(() => props.value)
const from = ref<Time>()
const to = ref<Time>()
const percent = ref<number>()

const minFrom = computed(() => props.minFrom)
const maxFrom = computed(() => props.maxFrom)
const minTo = computed(() => {
  if (from.value && !props.minTo) {
    return from.value
  }
  if (from.value && props.minTo && from.value > props.minTo) {
    return from.value
  }
  return props.minTo
})
const maxTo = computed(() => props.maxTo)

watch(localValue, (markupCondition) => {
  if (!markupCondition) {
    return
  }
  from.value = markupCondition.from
  to.value = markupCondition.to
  percent.value = markupCondition.percent
})

const clearForm = () => {
  from.value = undefined
  to.value = undefined
  percent.value = undefined
}

const closeModal = () => {
  clearForm()
  emit('close')
}

const markupConditionModalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!markupConditionModalForm.value?.reportValidity()) {
    return
  }
  if (!from.value || !to.value || !percent.value) {
    return
  }
  const payload: MarkupCondition = {
    from: from.value,
    to: to.value,
    percent: percent.value,
  }

  emit('submit', payload)
  clearForm()
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading as boolean"
    @close="closeModal"
  >
    <template #title>{{ title }}</template>

    <form ref="markupConditionModalForm" class="row g-3" @submit.prevent="onModalSubmit">
      <div class="col-md-6">
        <TimeSelect
          id="from"
          v-model="from"
          label="Начало"
          :min="minFrom"
          :max="maxFrom"
          :is-time-available="isTimeAvailable"
          required
        />
      </div>
      <div class="col-md-6">
        <TimeSelect
          id="to"
          v-model="to"
          label="Конец"
          :min="minTo"
          :max="maxTo"
          :is-time-available="isTimeAvailable"
          required
        />
      </div>
      <div class="col-md-12 field-required">
        <label for="markup">Наценка</label>
        <input
          id="markup"
          v-model="percent"
          type="number"
          class="form-control"
          required
        >
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
