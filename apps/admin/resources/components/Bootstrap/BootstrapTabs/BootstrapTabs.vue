<template>
  <div class="mb-4">
    <ul class="nav nav-underline border-bottom" role="tablist">
      <slot name="links" />
    </ul>
    <slot name="tabs" />
  </div>
</template>

<style scoped lang="scss">
ul.nav {
  margin-bottom: 25px;
}
</style>
