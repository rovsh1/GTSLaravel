<script lang="ts" setup>
import closeIcon from '@mdi/svg/svg/close.svg'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'

defineProps<{
  title: string
  description?: string
}>()

const emit = defineEmits<{
  (event: 'close'): void
}>()
</script>
<template>
  <div class="content">
    <div class="header">
      <div class="title">{{ title }}</div>
      <!-- TODO v-tooltip not initialized here, runtime warning -->
      <BootstrapButton
        label="Закрыть"
        :only-icon="closeIcon"
        variant="outline"
        severity="link"
        size="small"
        @click="emit('close')"
      />
    </div>
    <div v-if="description" class="description">{{ description }}</div>
  </div>
</template>
<style src="mosha-vue-toastify/dist/style.css" />
<style lang="scss">
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

@mixin variant($color, $border: true) {
  @include bs.color-bg($color);

  @if $border {
    $c: bs.color-contrast($color);

    --border-color: #{rgba($c, 0.1)};
  }
}

.mosha__toast {
  --border-color: transparent;

  min-height: unset;
  padding: unset;
  border: bs.$toast-border-width solid var(--border-color);
  border-radius: bs.$toast-border-radius;
  box-shadow: bs.$toast-box-shadow;

  &.default {
    @include variant(bs.$body-bg, false);
  }

  &.info {
    @include variant(bs.$info);
  }

  &.warning {
    @include variant(bs.$warning);
  }

  &.success {
    @include variant(bs.$success);
  }

  &.danger {
    @include variant(bs.$danger);
  }

  &__close-icon {
    &::before {
      color: inherit;
    }
  }

  &__content-wrapper {
    display: none;
  }
}
</style>
<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

.header {
  display: flex;
  gap: 1em;
  align-items: center;
  padding-inline: 0.75em 0.2em;
  padding-block: 0.2em;

  &:not(:last-child) {
    border-bottom: bs.$toast-border-width solid var(--border-color);
  }
}

.title {
  flex-grow: 1;
  font-weight: 700;
}

.description {
  padding: bs.$toast-padding-y bs.$toast-padding-x;
}
</style>
