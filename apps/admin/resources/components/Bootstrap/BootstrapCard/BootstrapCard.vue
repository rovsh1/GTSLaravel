<script lang="ts" setup>
withDefaults(defineProps<{
  existGrid: boolean
}>(), {
  existGrid: false,
})
</script>

<template>
  <div :class="[existGrid ? 'card card-grid' : 'card']">
    <div class="card-body unselectable">
      <slot />
    </div>
  </div>
</template>
<style lang="scss">
.card {
  margin-bottom: 1rem;

  .card-title {
    padding: 1rem 0 0;

    h2 {
      position: relative;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0 0.563rem 1rem;
      border-bottom: 1px solid #f2f2f2;
      font-size: 1rem;

      &::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 2rem;
        height: 0.188rem;
        background: #2584FA;
      }
    }
  }

  .card-body {
    padding-top: 0;

    table {
      caption {
        caption-side: top;
        font-weight: bold;
      }

      th {
        font-weight: normal;
      }
    }
  }
}

.card-grid {
  white-space: normal;

  table td:first-child,
  table th:first-child {
    padding-left: 1rem;
  }
}

</style>
