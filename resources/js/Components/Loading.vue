<template>
  <div
    class="skeleton"
    :style="styleObject"
  ></div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({

  width: {
    type: String,
    default: "100%"
  },

  height: {
    type: String,
    default: "16px"
  },

  rounded: {
    type: String,
    default: "sm"
  }

})

const radius = computed(() => {

    switch(props.rounded){

        case "full":
            return "9999px";

        case "md":
            return "8px";

        case "lg":
            return "12px";

        default:
            return "4px";

    }

})

const styleObject = computed(() => ({

    width: props.width,

    height: props.height,

    borderRadius: radius.value

}))
</script>

<style scoped>

.skeleton{

    background:linear-gradient(
        90deg,
        #eeeeee 25%,
        #f5f5f5 50%,
        #eeeeee 75%
    );

    background-size:200% 100%;

    animation:loading 1.4s infinite;

}

@keyframes loading{

    0%{

        background-position:200% 0;

    }

    100%{

        background-position:-200% 0;

    }

}

</style>