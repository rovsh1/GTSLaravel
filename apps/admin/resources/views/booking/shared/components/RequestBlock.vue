<script setup lang="ts">

type ColorVariant = 'warning' | 'success' | 'danger'

withDefaults(defineProps<{
  text: string
  showButton?: boolean
  buttonText?: string
  loading?: boolean
  variant?: ColorVariant
}>(), {
  showButton: true,
  loading: false,
  variant: 'warning',
  buttonText: 'Отправить запрос',
})

defineEmits<{
  (event: 'click'): void
}>()
</script>

<template>
  <div class="request-block" :class="[{ loading }, variant]">
    <span>{{ text }}</span>
    <a
      v-if="showButton"
      href="#"
      class="d-flex justify-content-center align-items-center"
      @click.prevent="$emit('click')"
    >
      {{ buttonText }}
    </a>
  </div>
</template>

<style scoped lang="scss">
.request-block {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: 0;
  padding: 0.438rem 9.75rem 0.438rem 1rem;
  border-radius: 4px;
  color: black;

  a {
    position: absolute;
    right: 0;
    width: 9rem;
    height: 100%;
    padding: 0 0.5rem;
    border-radius: 0 4px 4px 0;
    color: black;
    text-align: center;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  a::before {
    content: "";
    display: inline-block;
    vertical-align: middle;
    height: 100%;
  }

  &.warning {
    background: #fff4de;

    a {
      background: #ffa800;

      &:hover {
        background-color: #FFCA2CFF;
      }
    }
  }

  &.success {
    background: #d1e7dd;
    color: #0a3622;

    a {
      background: #198754;
      color: white;

      &:hover {
        background-color: #157347;
      }
    }
  }

  &.danger {
    background: #f8d7da;
    color: #58151c;

    a {
      background: #dc3545;
      color: white;

      &:hover {
        background-color: #bb2d3b;
      }
    }
  }
}
</style>
