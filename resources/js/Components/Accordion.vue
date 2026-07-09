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
        stroke-width="2"
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

    border-bottom:1px solid #e5e7eb;

}

.accordion-header{

    width:100%;

    padding:18px 20px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    background:white;

    border:none;

    cursor:pointer;

    font-size:16px;

    font-weight:600;

}

.accordion-header:hover{

    background:#f8fafc;

}

.icon{

    transition:.3s;

}

.rotate{

    transform:rotate(180deg);

}

.accordion-body{

    padding:0 20px 20px;

    color:#4b5563;

    line-height:1.8;

}

.accordion-enter-active,
.accordion-leave-active{

    transition:all .25s ease;

}

.accordion-enter-from,
.accordion-leave-to{

    opacity:0;

}
</style>