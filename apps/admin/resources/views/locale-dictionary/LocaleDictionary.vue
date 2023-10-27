<script lang="ts" setup>

import { onMounted, ref } from 'vue'

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

const show = ref<boolean>(false)

onMounted(async () => {

})
</script>
<template>
  <Card>
    <div>
      <div class="quicksearch-wrapper mt-3">
        <input type="search" class="form-control" placeholder="Быстрый поиск" autocomplete="off" />
        <button class="icon" type="submit" title="Найти">search</button>
      </div>
      <div class="position-relative">
        <OverlayLoading v-if="show" />
        <div v-if="!show" class="grid-empty-text">Записи отсутствуют</div>
        <table v-else class="table table-striped mt-3">
          <tbody>
            <tr>
              <td class="column-text column-source">admin</td>
              <td class="column-text column-source">admin</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </Card>
</template>

<style lang="scss" scoped>
@use "~resources/sass/vendor/bootstrap/configuration" as bs;

.table-striped {
  margin: 0;
}

.quicksearch-wrapper input {
  width: 100%;
}
</style>
