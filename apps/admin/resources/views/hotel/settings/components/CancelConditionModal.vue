<script setup lang="ts">

import { MaybeRef } from '@vueuse/core'

import { cancelPeriodOptions } from '~resources/views/hotel-booking/show/constants'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'

defineProps<{
  opened: MaybeRef<boolean>
  loading: MaybeRef<boolean>
  title: string
  // label: string
}>()

defineEmits<{
  (event: 'close'): void
  (event: 'submit'): void
}>()

// onMounted(() => {
//   useDateRangePicker(
//     document.querySelector<HTMLInputElement>('#period'),
//   )
// })

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="loading as boolean"
    @close="$emit('close')"
  >
    <template #title>{{ title }}</template>

    <form class="row g-3">
      <div class="col-md-12">
        <label for="period">Период</label>
        <input id="period" class="form-control">
      </div>
      <div class="col-md-12">
        <label for="markup">Наценка</label>
        <input id="markup" type="number" class="form-control">
      </div>

      <div class="col-md-12">
        <BootstrapSelectBase
          id="type"
          label="Процент от стоимости"
          :options="cancelPeriodOptions"
          value=""
        />
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="$emit('submit')">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
