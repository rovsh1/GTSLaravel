<script setup lang="ts">

defineProps<{
  tabName: string
  isRequired: Boolean
  isActive: Boolean
  isDisabled: Boolean
}>()
</script>

<template>
  <li class="nav-item" role="presentation">
    <a class="nav-link" :class="{ active: isActive, 'has-required-fields': isRequired, disabled: isDisabled }" data-bs-target="#main" data-bs-toggle="tab" type="button" role="tab">
      <slot />
    </a>
  </li>
</template>

<style scoped lang="scss">
a.has-required-fields::after {
  content: " *";
  color: #DC493A;
}

a.disabled {
  cursor: not-allowed;
}
</style>
