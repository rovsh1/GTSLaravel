<script lang="ts" setup>

import InlineIcon from '~components/InlineIcon.vue'
</script>

<template>
  <div>
    <button type="button">
      <InlineIcon icon="more_vert" class="buttonIcon" />
    </button>
    <div>
      <a class="d-flex align-items-center">
        <InlineIcon icon="edit_square" class="buttonIcon buttonIconEdit" />
        <span>Изменить цену</span>
      </a>
    </div>
  </div>
</template>

<style lang="scss" scoped>
div {
  position: relative;
  display: inline-block;

  .buttonIcon {
    font-size: 1.25rem;
  }

  .buttonIconEdit {
    font-size: 1rem;
  }

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
    min-width: 10rem;
    padding: 0.375rem 0.625rem;
    border-radius: 3px;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0,0,0,20%);
    line-height: 1;

    a {
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      color: #000;

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
