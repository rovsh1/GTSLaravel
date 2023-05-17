<script setup lang="ts">

defineEmits<{
  (event: 'edit'): void
  (event: 'delete'): void
}>()

</script>

<template>
  <div class="dropdown menu-actions-wrapper">
    <a href="#" class="dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="icon">more_vert</i>
    </a>

    <ul
      class="dropdown-menu"
      aria-labelledby="menu-actions"
      data-popper-placement="bottom-start"
    >
      <li>
        <a
          class="dropdown-item"
          href="#"
          @click.prevent="$emit('edit')"
        >
          <i class="icon">edit</i>
          Редактировать
        </a>
      </li>
      <li>
        <a
          class="dropdown-item"
          href="#"
          @click.prevent="$emit('delete')"
        >
          <i class="icon">delete</i>
          Удалить
        </a>
      </li>
    </ul>
  </div>
</template>

<style scoped lang="scss">
a i {
  font-weight: bold;
  font-size: 18px;
}

ul {
  position: absolute;
  inset: 0 auto auto 0;
  margin: 0;
  transform: translate3d(16px, 34px, 0);
}
</style>
