<script lang="ts" setup>
import RoomPriceTable from './RoomPriceTable.vue'

const props = withDefaults(defineProps<{
  title: string
  data: any
}>(), {
  data: [],
})
</script>

<template>
  <div class="card" data-id="170">
    <div class="card-title">
      <h2>{{ title }}</h2>
    </div>
    <div class="card-body unselectable">
      <RoomPriceTable :data="{}" />
    </div>
  </div>
</template>

<style lang="scss">
.card {
  margin-bottom: 16px;

  .card-title {
    padding: 16px 16px 0;

    h2 {
      position: relative;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0 9px 16px;
      border-bottom: 1px solid #f2f2f2;
      font-size: 16px;

      &::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 32px;
        height: 3px;
        background: #2584FA;
      }
    }
  }

  .card-body {
    padding-top: 0;

    table {
      caption {
        caption-side:top;
        font-weight: bold;
      }

      th {
        font-weight: normal;
      }
    }
  }
}

</style>
