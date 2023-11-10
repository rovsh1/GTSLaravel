<script setup lang="ts">

import { computed, ref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import TimePeriodSelect from '~resources/views/hotel/settings/components/TimePeriodSelect.vue'

import { MarkupCondition, Time, TimePeriod } from '~api/hotel/markup-settings'

import BaseDialog from '~components/BaseDialog.vue'

const props = withDefaults(defineProps<{
  value?: MarkupCondition
  opened: MaybeRef<boolean>
  title: string
  loading?: MaybeRef<boolean>
  freePeriods: TimePeriod[]
  isEditMode?: boolean
  min?: Time
  max?: Time
}>(), {
  loading: false,
  value: undefined,
  isEditMode: false,
  min: undefined,
  max: undefined,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit', value: MarkupCondition): void
}>()

const localValue = computed(() => props.value)
const from = ref<Time | null>(null)
const to = ref<Time | null>(null)
const percent = ref<number>()

const isLoading = computed(() => Boolean(props.loading))

watch(localValue, (markupCondition) => {
  if (!markupCondition) {
    return
  }
  from.value = markupCondition.from
  to.value = markupCondition.to
  percent.value = markupCondition.percent
})

const clearForm = () => {
  from.value = null
  to.value = null
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
    @keydown.enter="onModalSubmit"
    @close="closeModal"
  >
    <template #title>{{ title }}</template>

    <form ref="markupConditionModalForm" class="row g-3">
      <TimePeriodSelect
        v-model:from="from"
        v-model:to="to"
        :free-periods="freePeriods as TimePeriod[]"
        :is-edit-mode="isEditMode"
        :default-min="min"
        :default-max="max"
        required
      />
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
      <button class="btn btn-primary" type="button" :disabled="isLoading" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" :disabled="isLoading" @click="closeModal">Отмена</button>
    </template>
  </BaseDialog>
</template>
