<script setup lang="ts">
import AddConditionButton from '~resources/views/hotel/settings/components/AddConditionButton.vue'
import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'

import { MarkupCondition } from '~api/hotel/markup-settings'

defineProps<{
  conditions: MarkupCondition[]
  title: string
}>()

defineEmits<{
  (event: 'add'): void
  (event: 'edit', value: { condition: MarkupCondition; index: number }): void
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
      <tbody>
        <tr v-for="(condition, idx) in conditions" :key="`${condition.from}_${condition.to}`">
          <td>С {{ condition.from }} до {{ condition.to }}</td>
          <td>{{ condition.percent }}%</td>
          <td class="column-edit">
            <EditTableRowButton
              @click.prevent="$emit('edit', { condition, index: idx })"
            />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
