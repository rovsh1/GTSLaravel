<script setup lang="ts">

type ColorVariant = 'warning' | 'success' | 'danger'

withDefaults(defineProps<{
  text: string
  showButton?: boolean
  loading?: boolean
  variant?: ColorVariant
}>(), {
  showButton: true,
  loading: false,
  variant: 'warning',
})

defineEmits<{
  (event: 'click'): void
}>()
</script>

<template>
  <div class="request-block" :class="[{ loading }, variant]">
    {{ text }}
    <a
      v-if="showButton"
      href="#"
      @click.prevent="$emit('click')"
    >
      Отправить запрос
    </a>
  </div>
</template>

<style scoped lang="scss">
.request-block {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  padding: 7px 140px 7px 16px;
  border-radius: 4px;
  color: black;

  a {
    position: absolute;
    right: 0;
    display: inline-block;
    height: 100%;
    padding: 0 8px;
    border-radius: 0 4px 4px 0;
    color: black;
    vertical-align: middle;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }

  a::before {
    content: "";
    display: inline-block;
    height: 100%;
    vertical-align: middle;
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
