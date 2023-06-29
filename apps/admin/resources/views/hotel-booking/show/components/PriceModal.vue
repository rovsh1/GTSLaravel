<script setup lang="ts">

import { MaybeRef } from '@vueuse/core'

import BaseDialog from '~components/BaseDialog.vue'

defineProps<{
  opened: MaybeRef<boolean>
  header: string
  label: string
}>()

defineEmits<{
  (event: 'close'): void
  (event: 'submit'): void
}>()

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    @close="$emit('close')"
  >
    <template #title>{{ header }}</template>

    <form ref="modalForm" class="row g-3">
      <div class="col-md-12">
        <label for="brutto-price">{{ label }}</label>
        <input id="brutto-price" type="number" class="form-control">
      </div>
    </form>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="$emit('submit')">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
