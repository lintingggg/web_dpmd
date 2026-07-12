<template>
  <div class="accordion">

    <button
      class="accordion-header"
      @click="toggle"
    >

      <span>{{ title }}</span>

      <svg
        class="icon"
        :class="{ rotate: isOpen }"
        xmlns="http://www.w3.org/2000/svg"
        width="18"
        height="18"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
      >
        <polyline points="6 9 12 15 18 9"/>
      </svg>

    </button>

    <transition name="accordion">

      <div
        v-show="isOpen"
        class="accordion-body"
      >
        <slot />
      </div>

    </transition>

  </div>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({

  title: {
    type: String,
    required: true
  },

  defaultOpen: {
    type: Boolean,
    default: false
  }

})

const isOpen = ref(props.defaultOpen)

function toggle(){

    isOpen.value = !isOpen.value

}
</script>

<style scoped>

.accordion{
    border-bottom: 1px solid #e3e5e7;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

.accordion-header{
    width: 100%;
    padding: 18px 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border: none;
    cursor: pointer;
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    letter-spacing: -0.2px;
    transition: color 0.15s ease;
}

.accordion-header:hover{
    color: #222a3d;
}

.icon{
    transition: transform 0.25s ease;
    color: #9499a3;
    flex-shrink: 0;
}

.rotate{
    transform: rotate(180deg);
}

.accordion-body{
    padding: 0 4px 20px;
    font-size: 14px;
    font-weight: 500;
    color: #646a79;
    line-height: 1.75;
}

.accordion-enter-active,
.accordion-leave-active{
    transition: all 0.25s ease;
    overflow: hidden;
}

.accordion-enter-from,
.accordion-leave-to{
    opacity: 0;
    max-height: 0;
}

.accordion-enter-to,
.accordion-leave-from{
    opacity: 1;
    max-height: 500px;
}
</style>