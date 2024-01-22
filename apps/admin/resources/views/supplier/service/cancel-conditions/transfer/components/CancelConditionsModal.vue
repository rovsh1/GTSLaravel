<script setup lang="ts">

import { computed, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { ServiceCancelConditions } from '~api/supplier/cancel-conditions/transfer'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  opened: MaybeRef<boolean>
  loading: MaybeRef<boolean>
  modelValue: ServiceCancelConditions | null
  header?: string
}>()

const emit = defineEmits<{
  (event: 'update:modelValue', payload: ServiceCancelConditions | null): void
  (event: 'close'): void
  (event: 'submit'): void
}>()

const isLoading = computed(() => Boolean(props.loading))

const localValue = computed<ServiceCancelConditions | null>({
  get: () => props.modelValue,
  set: (val: ServiceCancelConditions | null) => emit('update:modelValue', val),
})

const cancelConditionsForm = ref()

const handleSubmit = () => {
  if (!cancelConditionsForm.value.reportValidity() || isLoading.value) {
    return
  }
  emit('submit')
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading"
    @close="$emit('close')"
    @keydown.enter="handleSubmit"
  >
    <template #title>{{ header || 'Условия отмены' }}</template>

    <form v-if="localValue" ref="cancelConditionsForm" class="row g-3">
      <div class="col-md-12 field-required">
        <label for="no-come-percent"><b>Неявка (% надбавки)</b></label>
        <input
          id="no-come-percent"
          v-model.number="localValue.noCheckInMarkup.percent"
          type="number"
          class="form-control"
          required
        >
      </div>

      <div class="col-md-6 field-required">
        <label for="days-count">Количество дней</label>
        <input
          id="days-count"
          v-model.number="localValue.dailyMarkups[0].daysCount"
          type="number"
          class="form-control"
          required
        >
      </div>

      <div class="col-md-6 field-required">
        <label for="days-percent">Процент надбавки</label>
        <input
          id="days-percent"
          v-model.number="localValue.dailyMarkups[0].percent"
          type="number"
          class="form-control"
          required
        >
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" :disabled="isLoading" @click="handleSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" :disabled="isLoading" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
