<script setup lang="ts">

import { MaybeRef } from '@vueuse/core'

import AddConditionButton from '~resources/views/settings/components/AddConditionButton.vue'
import EditTableRowButton from '~resources/views/settings/components/EditTableRowButton.vue'

import { MarkupCondition } from '~api/hotel/markup-settings'

withDefaults(defineProps<{
  conditions: MarkupCondition[]
  title: string
  loading: MaybeRef<boolean>
}>(), {
  loading: false,
})

defineEmits<{
  (event: 'add'): void
  (event: 'edit', value: { condition: MarkupCondition; index: number }): void
  (event: 'delete', value: number): void
}>()

// @todo если есть условие раннего или позднего, не могу менять базовое

</script>

<template>
  <div>
    <div class="d-flex align-items-center">
      <h6 class="mb-0">{{ title }}</h6>
      <AddConditionButton @click="$emit('add')" />
    </div>
    <table class="table">
      <tbody :class="{ loading: loading }">
        <tr v-for="(condition, idx) in conditions" :key="`${condition.from}_${condition.to}`">
          <td>С {{ condition.from }} до {{ condition.to }}</td>
          <td>{{ condition.percent }}%</td>
          <td class="column-edit">
            <EditTableRowButton
              @edit="$emit('edit', { condition, index: idx })"
              @delete="$emit('delete', idx)"
            />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
