<script setup lang="ts">

import { MaybeRef } from '@vueuse/core'

import { MarkupCondition } from '~api/hotel/markup-settings'

withDefaults(defineProps<{
  conditions: MarkupCondition[]
  title: string
  loading: MaybeRef<boolean>
}>(), {
  loading: false,
})

</script>

<template>
  <div>
    <div class="d-flex align-items-center">
      <h6 class="mb-0">{{ title }}</h6>
    </div>
    <table class="table">
      <tbody :class="{ loading: loading }">
        <template v-if="conditions.length">
          <tr v-for="(condition) in conditions" :key="`${condition.from}_${condition.to}`">
            <td>С {{ condition.from }} до {{ condition.to }}</td>
            <td>{{ condition.percent }}%</td>
          </tr>
        </template>
        <tr v-else>
          <td colspan="2" class="text-center">Записи отсутствуют</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
