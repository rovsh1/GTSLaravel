<script setup lang="ts">

import { requestInitialData } from 'gts-common/initial-data'
import { z } from 'zod'

const { timelineUrl } = requestInitialData(z.object({
  timelineUrl: z.string().optional(),
}))

</script>

<template>
  <div class="dropdown menu-actions-wrapper">
    <div
      v-if="timelineUrl"
      id="menu-actions"
      class="btn btn-menu"
      href="#"
      role="button"
      data-bs-toggle="dropdown"
      aria-expanded="true"
    >
      <i class="icon">more_vert</i>
    </div>

    <ul class="dropdown-menu" aria-labelledby="menu-actions" data-popper-placement="bottom-start">
      <li>
        <a class="dropdown-item" :href="timelineUrl">
          <i class="icon">history</i>
          История брони
        </a>
      </li>
    </ul>
  </div>
</template>

<style scoped lang="scss">
  a {
    cursor: pointer;
  }
</style>
