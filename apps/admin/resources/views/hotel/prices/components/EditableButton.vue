<script lang="ts" setup>
import InlineSVG from 'vue-inline-svg'

import dotsVerticalIcon from '@mdi/svg/svg/dots-vertical.svg'
import pencilIcon from '@mdi/svg/svg/square-edit-outline.svg'
</script>

<template>
  <div>
    <button type="button"><InlineSVG :src="dotsVerticalIcon" class="buttonIcon" /></button>
    <div>
      <a><InlineSVG :src="pencilIcon" class="buttonIcon" /> <span>Изменить цену</span></a>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div {
  position: relative;
  display: inline-block;

  button {
    padding: 0;
    border: none;
    background-color: transparent;
    outline: none;
  }

  & > div {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 25;
    display: none;
    overflow: hidden;
    min-width: 160px;
    padding: 6px 10px;
    border-radius: 3px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,20%);
    line-height: 1;

    a {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;

      span {
        text-align: right;
      }
    }
  }

  &:hover > div {
    display:block;
  }
}

svg {
  width: 16px;
}
</style>
