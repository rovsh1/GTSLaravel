<script setup lang="ts">
import { MaybeRef } from '@vueuse/core'

import AddConditionButton from '~resources/views/hotel/settings/components/AddConditionButton.vue'
import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'

import { DailyMarkup } from '~api/hotel/markup-settings'

withDefaults(defineProps<{
  conditions: DailyMarkup[]
  title: string
  loading: MaybeRef<boolean>
}>(), {
  loading: false,
})

defineEmits<{
  (event: 'add'): void
  (event: 'edit', value: { condition: DailyMarkup; index: number }): void
  (event: 'delete', value: number): void
}>()

</script>

<template>
  <div>
    <div class="d-flex">
      <h6>{{ title }}</h6>
      <AddConditionButton
        @click.prevent="$emit('add')"
      />
    </div>
    <table class="table">
      <tbody :class="{ loading: loading }">
        <tr v-for="(condition, idx) in conditions" :key="idx">
          <td>{{ condition.daysCount }}</td>
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
