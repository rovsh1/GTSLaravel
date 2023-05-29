<script setup lang="ts">
defineProps<{
  modelValue: number
}>()
</script>

<template>
  <div class="dropdown">
    <button
      class="btn btn-secondary dropdown-toggle w-25 align-left"
      type="button"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      Новая
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="#">В работе</a></li>
      <li><a class="dropdown-item" href="#">Отменена</a></li>
    </ul>
  </div>
</template>

<style scoped lang="scss">
.dropdown {
  button {
    min-width: 9rem;
    font-weight: bold;
    text-align: left;
  }
}
</style>
