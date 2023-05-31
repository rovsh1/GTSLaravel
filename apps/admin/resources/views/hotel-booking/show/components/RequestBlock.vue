<script setup lang="ts">
withDefaults(defineProps<{
  text: string
  showButton?: boolean
  loading?: boolean
}>(), {
  showButton: true,
  loading: false,
})

defineEmits<{
  (event: 'click'): void
}>()
</script>

<template>
  <div class="request-block" :class="{ loading }">
    {{ text }}
    <a v-if="showButton" href="#" @click.prevent="$emit('click')">Отправить запрос</a>
  </div>
</template>

<style scoped lang="scss">
.request-block {
  position: relative;
  margin-bottom: 10px;
  padding: 7px 140px 7px 16px;
  border-radius: 4px;
  background: #fff4de;
  color: black;

  a {
    position: absolute;
    top: 0;
    right: 0;
    display: block;
    padding: 0 8px;
    border-radius: 0 4px 4px 0;
    background: #ffa800;
    color: black;
    line-height: 33px;
    transition: color 0.15s ease-in-out,background-color 0.15s ease-in-out,border-color 0.15s ease-in-out,box-shadow 0.15s ease-in-out;

    &:hover {
      background-color: #FFCA2CFF;
    }
  }
}
</style>
